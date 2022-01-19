<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ability extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    public function userAbilities(){
        return $this->hasMany(UserAbility::class, 'ability_id');
    }
}