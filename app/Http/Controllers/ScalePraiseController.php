<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ScalePraiseController extends Controller
{
    public function index(){
        return view('scale_praise.index');
    }
    public function my(){
        return view('scale_praise.my');
    }
    public function create(){
        return view('scale_praise.create');
    }
    public function store(){
        // return redirect()->route('scale_praise.my')->with(
        //     'message',
        //     ''
        // );
    }
}