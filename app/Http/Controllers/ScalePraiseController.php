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
    $ministers = MinisterScale::with(['scale_praises' => function($query){
      $query->with('praise');
    }])->whereNotNull('scale_id')
       ->whereMinistryId(auth()->user()->current_ministry)
       ->orderBy('created_at','desc')->get();

    $ministers = $ministers->map(function($minister){
      if($minister->scale){
        $date = Carbon::createFromFormat('Y-m-d', $minister->scale->date);
        $minister->scale->date_formatted = $date->format('d/m');
        $minister->scale->weekday_formatted = User::getAvailableWeekdays($minister->scale->weekday);
      
        $arrDate = explode('-',$minister->scale->date);
        $minister->scale->day = count($arrDate) == 3 ? $arrDate[2] : $minister->scale->date;
        $minister->scale->month = count($arrDate) == 3 ? $arrDate[1] : $minister->scale->date;
        $minister->scale->weekday_name = User::getAvailableWeekdays($minister->scale->weekday);
        
        $minister->header = "*Escala ".$minister->scale->day."/".$minister->scale->month;
        $minister->header.= " | ".$minister->scale->weekday_name;
        $minister->header.= " - ".$minister->scale->hour."*\n";
        $minister->header.= "_".$minister->scale->theme."_\n\n";
      }else{
        $minister->header = "*Escala Particular*\n";
        $minister->header.= "_".$minister->user->name."_\n\n";
      }

      return $minister;
    });

    return view('scale_praise.index',[
      'ministers' => $ministers
    ]);
  }
  public function my(){
    $ministers = MinisterScale::with(['scale_praises' => function($query){
      $query->with('praise');
    }])->whereUserId(auth()->user()->id)
       ->whereMinistryId(auth()->user()->current_ministry)
       ->orderBy('created_at','desc')->get();

    $ministers = $ministers->map(function($minister){
      if($minister->scale){
        $date = Carbon::createFromFormat('Y-m-d', $minister->scale->date);
        $minister->scale->date_formatted = $date->format('d/m');
        $minister->scale->weekday_formatted = User::getAvailableWeekdays($minister->scale->weekday);
      
        $arrDate = explode('-',$minister->scale->date);
        $minister->scale->day = count($arrDate) == 3 ? $arrDate[2] : $minister->scale->date;
        $minister->scale->month = count($arrDate) == 3 ? $arrDate[1] : $minister->scale->date;
        $minister->scale->weekday_name = User::getAvailableWeekdays($minister->scale->weekday);
        
        $minister->header = "*Escala ".$minister->scale->day."/".$minister->scale->month;
        $minister->header.= " | ".$minister->scale->weekday_name;
        $minister->header.= " - ".$minister->scale->hour."*\n";
        $minister->header.= "_".$minister->scale->theme."_\n\n";
      }else{
        $minister->header = "*Escala Particular*\n";
        $minister->header.= "_".$minister->user->name."_\n\n";
      }

      return $minister;
    });

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
  public function edit($id){
    if(!$minister = MinisterScale::whereId($id)
      ->whereUserId(auth()->user()->id)
      ->first()
    ) return redirect()->back()->with(
      'message',
      'Escala não encontrada, ou você não tem permissão para editar essa escala'
    );
        
    if($scale = $minister->scale){
      $date = Carbon::createFromFormat('Y-m-d', $scale->date);
      $scale->date_formatted = $date->format('d/m');
      $scale->weekday_formatted = User::getAvailableWeekdays($scale->weekday);
    }

    $minister->praises_added = $minister->scale_praises->map(function($scale_praise){
      return (object)[
        "id" => $scale_praise->praise_id,
        "name" => $scale_praise->praise->name,
        "singer" => $scale_praise->praise->singer,
        "youtube" => $scale_praise->youtube_link,
        "cipher" => $scale_praise->cipher_link,
        "tone" => $scale_praise->tone,
        "legend" => $scale_praise->legend,
        "index" => $scale_praise->index
      ];
    });
    
    return view('scale_praise.create.index',[
      'scale' => $scale,
      'minister' => $minister
    ]);
  }
  public function show($id){
    if(!$minister = MinisterScale::whereId($id)->first()) return redirect()->back()->with(
      'message', 'Escala não encontrada'
    );
        
    if($scale = $minister->scale){
      $date = Carbon::createFromFormat('Y-m-d', $scale->date);
      $scale->date_formatted = $date->format('d/m');
      $scale->weekday_formatted = User::getAvailableWeekdays($scale->weekday);
    }

    $minister->praises_added = $minister->scale_praises->map(function($scale_praise){
      return (object)[
        "id" => $scale_praise->praise_id,
        "name" => $scale_praise->praise->name,
        "singer" => $scale_praise->praise->singer,
        "youtube" => $scale_praise->youtube_link,
        "cipher" => $scale_praise->cipher_link,
        "tone" => $scale_praise->tone,
        "legend" => $scale_praise->legend,
        "index" => $scale_praise->index
      ];
    });
    
    if($minister->scale){
      $date = Carbon::createFromFormat('Y-m-d', $minister->scale->date);
      $minister->scale->date_formatted = $date->format('d/m');
      $minister->scale->weekday_formatted = User::getAvailableWeekdays($minister->scale->weekday);
    
      $arrDate = explode('-',$minister->scale->date);
      $minister->scale->day = count($arrDate) == 3 ? $arrDate[2] : $minister->scale->date;
      $minister->scale->month = count($arrDate) == 3 ? $arrDate[1] : $minister->scale->date;
      $minister->scale->weekday_name = User::getAvailableWeekdays($minister->scale->weekday);
      
      $minister->header = "*Escala ".$minister->scale->day."/".$minister->scale->month;
      $minister->header.= " | ".$minister->scale->weekday_name;
      $minister->header.= " - ".$minister->scale->hour."*\n";
      $minister->header.= "_".$minister->scale->theme."_\n\n";
    }else{
      $minister->header = "*Escala Particular*\n";
      $minister->header.= "_".$minister->user->name."_\n\n";
    }

    return view('scale_praise.show',[
      'scale' => $scale,
      'minister' => $minister
    ]);
  }
  public function store(Request $request){
    $data = [
      'scale_id' => $request->scale_id ?? null,
      'user_id' => auth()->user()->id,
      'verse' => $request->verse ?? null,
      'about' => $request->about ?? null,
      'privacy' => $request->privacy ? 'public' : 'private',
      'playlist' => $request->playlist ?? null,
      'ministry_id' => auth()->user()->current_ministry
    ];
    
    if($request->id){
      if(!$scale = MinisterScale::whereId($request->id)
        ->whereUserId(auth()->user()->id)
        ->first()
      ) return response()->json([
        'result' => false,
        'response' => 'Escala não encontrada, ou você não tem permissão para editá-la'
      ]);

      $scale->update($data);
      foreach($scale->scale_praises as $scale_praise){
        $scale_praise->delete();
      }
    }else{
      if($request->scale_id) $scale = MinisterScale::updateOrCreate([
        'scale_id' => $request->scale_id,
        'user_id' => auth()->user()->id,
        'ministry_id' => auth()->user()->current_ministry
      ],$data);
      else $scale = MinisterScale::create($data);
    }

    if(!$scale) return response()->json([
      'result' => false,
      'response' => 'Houve um erro ao criar a escala'
    ]);
      
    foreach($request->praises as $index => $req_praise){
      if($req_praise['id']) $praise = Praise::whereMinistryId(auth()->user()->current_ministry)
        ->whereId($req_praise['id'])
        ->whereName($req_praise['name'])
        ->whereSinger($req_praise['singer'])
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
        'legend' => $req_praise['legend'] ?? null,
        'index' => $req_praise['index'] ?? ($index + 1),
        'praise_id' => $praise->id ?? null,
        'user_id' => auth()->user()->id,
        'minister_scale_id' => $scale->id,
      ];

      ScalePraise::create($item);
    }

    $scale = MinisterScale::with(['scale_praises' => function($query){
      $query->with('praise');
    }])->whereId($scale->id)->first();

    if($scale->scale){
      $arrDate = explode('-',$scale->scale->date);
      $scale->scale->day = count($arrDate) == 3 ? $arrDate[2] : $scale->scale->date;
      $scale->scale->month = count($arrDate) == 3 ? $arrDate[1] : $scale->scale->date;
      $scale->scale->weekday_name = User::getAvailableWeekdays($scale->scale->weekday);
      
      $scale->header = "*Escala ".$scale->scale->day."/".$scale->scale->month;
      $scale->header.= " | ".$scale->scale->weekday_name;
      $scale->header.= " - ".$scale->scale->hour."*\n";
      $scale->header.= "_".$scale->scale->theme."_\n\n";
    }else{
      $scale->header = "*Escala Particular*\n";
      $scale->header.= "_".$scale->user->name."_\n\n";
    }

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