<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\CashController;

class Cash extends Model{
  use HasFactory;
  
  protected $fillable = [
    'name',
    'total',
    'goal',
    'ministry_id',
    'disabled'
  ];

  public function ministry(){
    return $this->belongsTo(Ministry::class, 'ministry_id');
  }
  public function cashGoals(){
    return $this->hasMany(CashGoal::class, 'cash_id');
  }
  public function cashLaunches(){
    return $this->hasMany(CashLaunch::class, 'cash_id');
  }

  public function loadData(){
    $this->total_formatted = CashController::FormatMoney(
      $this->total
    );
    $this->goal_formatted = CashController::FormatMoney(
      $this->goal
    );

    return $this;
  }
}