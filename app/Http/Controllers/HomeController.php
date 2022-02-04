<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Scale;
use App\Models\Praise;
use App\Models\Ability;

class HomeController extends Controller
{
  public function index(){
    $next_scale = Scale::join('scale_users', 'scales.id', '=', 'scale_users.scale_id')
      ->where('ministry_id', auth()->user()->current_ministry)
      ->whereDate('scales.date','>=',Carbon::now())
      ->where('scales.published', true)
      ->where('scale_users.user_id', auth()->user()->id)
      ->orderBy('scales.date')
      ->select('scale_users.*','scale_users.id as scale_user_id','scales.*')
      ->first();

    if($next_scale){
      $date = Carbon::createFromFormat('Y-m-d', $next_scale->date);
      $next_scale->date_formatted = $date->format('d/m');
      $next_scale->weekday_formatted = User::getAvailableWeekdays($next_scale->weekday);
      $next_scale->abilities = Ability::whereIn('slug',explode(',',$next_scale->ability))->get();
      $next_scale->is_minister = !!$next_scale->abilities->where('slug','ministro')->first();
      $next_scale->minister_scales = $next_scale->ministerScales()->where('privacy','public')->get();
    }

    return view('home.index',[
      'next_scale' => $next_scale
    ]);
  }
}