<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAvailability extends Model
{
  use HasFactory;

  protected $fillable = [
    'date',
    'date_final',
    'user_id',
    'is_unavailable',
    'ministry_id',
    'obs'
  ];

  #region RELATIONSHIPS
  public function user(){
    return $this->belongsTo(User::class, 'user_id');
  }
  public function ministry(){
    return $this->belongsTo(Ministry::class, 'ministry_id');
  }
  #endregion RELATIONSHIPS
}