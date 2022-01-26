<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;

use App\Models\Praise;
use App\Models\PraiseCipher;
use App\Models\PraiseYoutube;
use App\Models\FavoritePraise;
use App\Services\OfficeService;

class PraiseController extends Controller
{
    public function index(){
        $praises = Praise::orderBy('updated_at','desc')->get();
        return view('praise.index', ['praises' => $praises]);
    }
    public function favorite(){
        $praises = auth()->user()->favoritePraises;
        return view('praise.favorite', ['praises' => $praises]);
    }
    public function create($import = null){
        if($import != 'importar') $import = null;
        return view('praise.create',[
            'import' => $import
        ]);
    }
    public function store(Request $request){
        if($request->file('import')){
            return $this->handleImport($request->file('import'));
        }
        $data = [
            'name' => $request->name,
            'singer' => $request->singer,
        ];
        if(!$praise = Praise::updateOrCreate($data,$data + [
            'tags' => $request->has('tags') && strlen($request->tags) > 0 ? $request->tags : null
        ])) return redirect()->back()->with(
            'message',
            'Houve um erro ao adicionar/editar este louvor'
        );

        if($request->has('youtube') && strlen($request->youtube) > 0){
            $data=[
                'link' => $request->youtube,
                'praise_id' => $praise->id,
            ];
            PraiseYoutube::updateOrCreate($data,$data);
        }
        if($request->has('cipher') && strlen($request->cipher) > 0){
            $data=[
                'link' => $request->cipher,
                'praise_id' => $praise->id,
            ];
            PraiseCipher::updateOrCreate($data,$data);
        }

        return redirect()->back()->with(
            'message',
            'Louvor adicionado/editado com sucesso'
        );
    }
    public function toggleFavorite(Request $request){
        if(!$praise = Praise::whereId($request->id)->first()) return response()->json([
            'result' => false,
            'response' => 'Louvor não encontrado'
        ]);
        if($favorite = $praise->favorite()) $favorite->delete();
        else{
            if($request->youtube_link) $youtube_link = $request->youtube_link;
            else{
                $main = $praise->mainYoutube();
                $youtube_link = $main ? $main->link : null;
            }
            if($request->cipher_link) $cipher_link = $request->cipher_link;
            else{
                $main = $praise->mainCipher();
                $cipher_link = $main ? $main->link : null;
            }
            if($request->tone) $tone = $request->tone;
            else $tone = $request->cipher_link ? $request->cipher_link->original_tone : null;
                        
            FavoritePraise::create([
                'praise_id' => $praise->id,
                'user_id' => auth()->user()->id,
                'youtube_link' => $youtube_link,
                'cipher_link' => $cipher_link,
                'tone' => $tone,
            ]);
        }

        return response()->json([
            'result' => true,
            'response' => 'Alterado com sucesso'
        ]);
    }
    protected function handleImport($file){
        $sheet = new OfficeService($file->getRealPath());
        $praises = $sheet->loadPraises();
        $errors = [];
        $countSuccess = 0;
        foreach($praises as $praise){
            try{
                Praise::updateOrCreate($praise,$praise);
                $countSuccess++;
            }catch(Exception $e){
                $errors[] = $praise;
            }   
        }
        if($countSuccess == 0 && count($errors)) $message = "Nenhum louvor foi importado";
        else{
            $message = "";
            if($countSuccess > 0){
                $message.= $countSuccess == 1 ? "1 louvor importado com sucesso<br/>":"$countSuccess louvores importados com sucesso<br/>";
            }
            if(count($errors) > 0){
                $message.=  count($errors) == 1 ? "1 louvor não pode ser importado<br/>":count($errors)." louvores não puderam ser importados:<br/>";

                $listErrors = array_map(function($error){
                    return "<pre>".json_encode($error)."</pre><hr/>";
                }, $errors);

                $message.= implode('<br/>',$listErrors);
            }
        }
        return redirect()->route('praise.index')->with('message',$message);
    }
}