<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Cash;
use App\Models\CashGoal;

class CashController extends Controller
{
  public function index($id){
    $cash = Cash::whereId($id)->whereMinistryId(
      auth()->user()->current_ministry
    )->first();

    if(!$cash) return redirect()->back()->with(
      'notify-type', 'danger'
    )->with('notify', 'Caixa não encontrado');

    $cash->loadData();

    $launchs = [...$cash->cashLaunches->map(function($launch){
      return $launch->loadData();
    })->reverse()];
    
    $goals = $cash->cashGoals->map(function($goal){
      return $goal->loadData();
    });

    $resume = $this->getResume(
      auth()->user()->current_ministry, $id,
      $cash, $launchs, $goals
    );

    return view('cash.index', [
      'cash' => $cash,
      'launchs' => $launchs,
      'goals' => $goals,
      'resume' => $resume
    ]);
  }
  // [ ] REFATORAR getResume EM: MINISTRY.BLADE.PHP
  public function getResume($ministry_id, $id, $cash = null, $launchs = null, $goals = null){
    if(!$cash) $cash = Cash::whereId($id)->whereMinistryId(
      $ministry_id
    )->first();
    if(!$cash) return null;
    
    if(!$launchs) $launchs = [...$cash->cashLaunches->map(function($launch){
      return $launch->loadData();
    })->reverse()];
    
    if(!$goals) $goals = $cash->cashGoals->map(function($goal){
      return $goal->loadData();
    });

    $goals = $goals->filter(function($goal){ return !$goal->is_completed; });

    $total = array_sum(array_column($launchs, 'value'));
    $valueToGoal = null;
    $parcentToGoal = null;
    if(count($goals) > 0){
      $nextGoal = $goals[0];
      if($nextGoal->value){
        $valueToGoal = $nextGoal->value - $total;
        if($valueToGoal < 0) $valueToGoal = 0; 
  
        $parcentToGoal = $valueToGoal > 0 ? round(($total * 100) / $valueToGoal) : 100;
      }
    }

    return (object)[
      'total' => $total,
      'total_formatted' => $this->formatMoney($total),
      'value_to_goal' => $valueToGoal,
      'value_to_goal_formatted' => $valueToGoal ? $this->FormatMoney($valueToGoal) : 0,
      'percent_to_goal' => (int) $parcentToGoal
    ];
  }
  #region STATIC FUNCTIONS
  public static function FormatMoney($value){
    if(!$value && $value !== 0) return '??';
    return ($value < 0 ? 
      '-R$ ' . number_format($value * -1, 2 ,',','.'):
      'R$ ' . number_format($value, 2 ,',','.')
    );
  }
  #endregion STATIC FUNCTIONS
}