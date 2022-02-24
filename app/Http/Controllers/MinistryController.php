<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ministry;
use App\Models\UserMinistry;

class MinistryController extends Controller
{
    public function index($slug){
        if(!$ministry = Ministry::whereSlug($slug)->first()) return redirect()->back()->with(
            'message',
            'Ministério não encontrado'
        );

        return view('ministry.index',['ministry' => $ministry]);
    }
    public function create(){
        $this->adminOnly();
        return view('ministry.create');
    }
    public function store(Request $request){
        $this->adminOnly();
        $slug = $this->handleMinistrySlug($request->name);

        $data = [
            'name' => $request->name,
            'slug' => $slug,
            'free_entry' => $request->has('free_entry'),
            'description' => $request->description,
            'user_id' => auth()->user()->id
        ];
        if($request->file('image')){
            ['names' => $names,'errors' => $errors] = $this->uploadImages(
                [$request->file('image')],
                "uploads/ministry/" . auth()->user()->id . "/"
            );
            
            if(count($errors) > 0 || count($names) == 0) return redirect()->back()->with(
                'message', 'Não foi possível salvar essa imagem'
            );

            $data+=['image' => $names[0]];
        }

        if(!$ministry = Ministry::create($data)) return redirect()->back()->with(
            'message', 'Houve um erro ao criar este ministério'
        );

        UserMinistry::create([
            'user_id' => auth()->user()->id,
            'ministry_id' => $ministry->id,
        ]);

        auth()->user()->selectMinistry($ministry->id);

        return redirect()->route('home')->with(
            'message',
            'Ministério Cadastrado com Sucesso'
        );
    }
    public function select($id){
        if(auth()->user()->current_ministry != $id){
            if(!auth()->user()->selectMinistry($id)) return redirect()->back()->with(
                'message',
                'Você não faz parte deste ministério'
            );
            return redirect()->back();
        }
        return redirect()->route('ministry.index', ['slug' => auth()->user()->currentMinistry->slug]);

    }
    public function outhers(){
        $ids = array_column(auth()->user()->ministries->toArray(), 'id');
        $ministries = Ministry::whereNotIn('id',$ids)->get();
        return view('ministry.outhers',['ministries' => $ministries]);
    }
    public function bind($slug){
        if(!$ministry = Ministry::whereSlug($slug)->first()) return redirect()->back()->with(
            'message',
            'Ministério não encontrado'
        );
        if($ministry->userMinistry()->whereUserId(auth()->user()->id)->first()) return redirect()->back()->with(
            'message',
            'Você já faz parte deste ministério'
        );

        UserMinistry::create([
            'user_id' => auth()->user()->id,
            'ministry_id' => $ministry->id,
            'status' => $ministry->free_entry ? 'active':'disabled'
        ]);

        auth()->user()->selectMinistry($ministry->id);
        return redirect()->route('home')->with(
            'message',
            $ministry->free_entry ? 'Parabéns, você ingressou em um novo ministério': 'Parabéns, você solicitou a entrada em um novo ministério. Assim que autorizarem sua solicitação você terá acesso a todo o conteúdo'
        );
    }
    protected function handleMinistrySlug($name){
        $slug = $this->generateSlug($name);
        $slugs = array_column(Ministry::where('slug', 'like', $slug.'%')
            ->select('slug')
            ->get()
            ->toArray(),
            'slug'
        );

        $increment = "";
        $count = 0;
        while(array_search($slug.$increment, $slugs) !== false){
            $count++;
            $increment = "-$count";
        }
        return $slug.$increment;
    }
}