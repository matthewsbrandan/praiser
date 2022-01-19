<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAbility extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ability_id',
        'exp',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function ability(){
        return $this->belongsTo(Ability::class, 'ability_id');
    }
}