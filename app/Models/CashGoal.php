<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\CashController;

class CashGoal extends Model
{
  use HasFactory;

  protected $fillable = [
    'title',
    'image',
    'value',
    'value_max',
    'links',
    'is_completed',
    'cash_id'
  ];

  public function cash(){
    return $this->belongsTo(Cash::class, 'cash_id');
  }
  
  public function loadData(){
    if($this->value) $this->value_formatted = CashController::FormatMoney(
      $this->value
    );
    if($this->value_max) $this->value_max_formatted = CashController::FormatMoney(
      $this->value_max
    );
    if($this->links && is_string($this->links)) $this->links = json_decode(
      $this->links,
      false
    );
    
    return $this;
  }
}
