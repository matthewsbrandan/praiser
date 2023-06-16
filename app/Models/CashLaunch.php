<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Http\Controllers\CashController;

class CashLaunch extends Model{
  use HasFactory;

  protected $fillable = [
    'title',
    'description',
    'value',
    'date',
    'type',
    'cash_id'
  ];

  public function cash(){
    return $this->belongsTo(Cash::class, 'cash_id');
  }

  public function loadData(){
    $this->value_formatted = CashController::FormatMoney(
      $this->value
    );
    $this->date_formatted = implode('/', array_reverse(
      explode('-', $this->date)
    ));
    return $this;
  }
}