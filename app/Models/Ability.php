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
        'image'
    ];

    #region RELATIONSHIP    
    public function userAbilities(){
        return $this->hasMany(UserAbility::class, 'ability_id');
    }
    #endregion RELATIONSHIP
    #region FORMATTED
    public function getImage(){
        return $this->image ?? Ability::getDefaultImage();
    }
    #endregion FORMATTED
    #region STATIC
    public static function getDefaultImage(){
        return asset('assets/img/abilities-default.jpg');
    }
    #endregion STATIC
}