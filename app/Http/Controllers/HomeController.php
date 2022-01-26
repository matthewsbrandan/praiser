<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Praise;

class HomeController extends Controller
{
  public function index(){
    $num_praises = Praise::count();
    return view('home.index',[
      'num_praises' => $num_praises
    ]);
  }
}