<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Scale;
use App\Models\Praise;
use App\Models\PraiseYoutube;
use App\Models\PraiseCipher;
use App\Models\ScalePraise;
use App\Models\MinisterScale;

class ScalePraiseController extends Controller
{
  public function index(){
    return view('scale_praise.index');
  }
  public function my(){
    $ministers = MinisterScale::with(['scale_praises' => function($query){
      $query->with('praise');
    }])->whereUserId(auth()->user()->id)
       ->whereMinistryId(auth()->user()->current_ministry)
       ->orderBy('created_at','desc')->get();

    return view('scale_praise.my',[
      'ministers' => $ministers
    ]);
  }
  public function create($scale_id = null){
    $scale = null;
    if($scale_id){
      if(!$scale = Scale::whereId($scale_id)->first()) return redirect()->back()->with(
        'notify','Escala não encontrada'
      )->with('notify-type','danger');
      if(!$scale->scaleUsers()
        ->whereUserId(auth()->user()->id)
        ->where('ability','like','%ministro%')
        ->first()
      ) return redirect()->back()->with(
        'notify','Você não está ministrando nesta escala'
      )->with('notify-type','danger');

      $date = Carbon::createFromFormat('Y-m-d', $scale->date);
      $scale->date_formatted = $date->format('d/m');
      $scale->weekday_formatted = User::getAvailableWeekdays($scale->weekday);
    }
    return view('scale_praise.create.index',[
      'scale' => $scale
    ]);
  }
  public function store(Request $request){
    $data = [
      'scale_id' => $request->scale_id ?? null,
      'user_id' => auth()->user()->id,
      'verse' => $request->verse ?? null,
      'about' => $request->about ?? null,
      'privacy' => $request->has('privacy') ? 'public' : 'private',
      'playlist' => $request->playlist ?? null,
      'ministry_id' => auth()->user()->current_ministry
    ];
    
    if(!$scale = MinisterScale::create($data)) return response()->json([
      'result' => false,
      'response' => 'Houve um erro ao criar a escala'
    ]);
      
    foreach($request->praises as $req_praise){
      if($req_praise['id']) $praise = Praise::whereMinistryId(auth()->user()->current_ministry)
        ->whereId($req_praise['id'])
        ->first();

      if(!$req_praise['id'] || !$praise){
        if(!$praise = Praise::whereMinistryId(auth()->user()->current_ministry)
          ->whereName($req_praise['name'])
          ->whereSinger($req_praise['singer'])
          ->first()
        ) $praise = Praise::create([
          'name' => $req_praise['name'],
          'singer' => $req_praise['singer'],
          'ministry_id' => auth()->user()->current_ministry,
        ]);
      }

      if(!$praise) continue;
      
      if($req_praise['youtube']){
        $dy = [
          'link' => $req_praise['youtube'],
          'praise_id' => $praise->id,
        ];

        PraiseYoutube::updateOrCreate($dy,$dy);
      }
      if($req_praise['cipher']){
        if($cipher = $praise->ciphers()->whereLink($req_praise['cipher'])->first()){
          if($req_praise['tone'] && !$cipher->original_tone) $cipher->update([
            'original_tone' => $req_praise['tone']
          ]);
        }
        else PraiseCipher::create([
          'link' => $req_praise['cipher'],
          'original_tone' => $req_praise['tone'] ?? null,
          'praise_id' => $praise->id
        ]);
      }

      $item = [
        'youtube_link' => $req_praise['youtube'],
        'cipher_link' => $req_praise['cipher'] ?? null,
        'tone' => $req_praise['tone'] ?? null,
        'praise_id' => $praise->id ?? null,
        'user_id' => auth()->user()->id,
        'minister_scale_id' => $scale->id,
      ];

      ScalePraise::create($item);
    }

    $scale = MinisterScale::with(['scale_praises' => function($query){
      $query->with('praise');
    }])->whereId($scale->id)->first();

    return response()->json([
      'result' => true,
      'response' => $scale
    ]);
  }
  public function delete($id){
    if(!$minister = MinisterScale::whereId($id)
      ->whereUserId(auth()->user()->id)
      ->whereMinistryId(auth()->user()->current_ministry)
      ->first()
    ) return redirect()->back()->with(
      'notify',
      'Escala não encontrada, ou você não tem permissão para excluí-la'
    )->with('notify-type', 'danger');

    foreach($minister->scale_praises as $praise){
      $praise->delete();
    }
    $minister->delete();

    return redirect()->route('scale_praise.my')->with(
      'notify',
      'Escala excluída com sucesso'
    );
  }
}