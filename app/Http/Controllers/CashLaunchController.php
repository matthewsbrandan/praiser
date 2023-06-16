<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CashLaunch;

class CashLaunchController extends Controller
{
  public function store(Request $request, $id){
    if(
      !$request->title ||
      !$request->description ||
      !$request->value ||
      !$request->date
    ) return redirect()->back()->with(
      'notify', 'Os campos título, descrição, valor e data são obrigatórios'
    )->with('notify-type', 'danger');

    if(auth()->user()->currentMinistry){
      if(
        auth()->user()->currentMinistry->user_id !== auth()->user()->id
      ) return redirect()->back()->with(
        'notify', 'Você não é administrador desse ministério'
      )->with('notify-type', 'danger');


      if(!auth()->user()->currentMinistry->cashes || !in_array(
        (int) $id, auth()->user()->currentMinistry->cashes->map(
          function($cash){ return $cash->id; }
        )->toArray()
      )) return redirect()->back()->with(
        'notify', 'Este caixa não pertence ao seu ministério atual'
      )->with('notify-type', 'danger');
    }

    if($request->value === 0) return redirect()->back()->with(
      'notify', 'O valor do lançamento não pode ser igual a 0'
    )->with('notify-type', 'danger');

    $isIncome = $request->has('income');
    if($isIncome) $request->value = $request->value > 0 ? $request->value : (
      $request->value * -1
    );
    else $request->value = $request->value < 0 ? $request->value : (
      $request->value * -1
    );

    CashLaunch::create([
      'title' => $request->title,
      'description' => $request->description,
      'value' => $request->value,
      'date' => $request->date,
      'type' => $isIncome ? 'income' : 'expense',
      'cash_id' => $id
    ]);

    return redirect()->back()->with(
      'notify', 'Novo lançamento adicionado com sucesso'
    )->with('notify-type', 'success');
  }
}