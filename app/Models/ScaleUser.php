<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScaleUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'scale_id',
        'user_id',
        'nickname',
        'ability',
    ];

    #region RELATIONSHIP
    public function scale(){
        return $this->belongsTo(Scale::class, 'scale_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    #endregion RELATIONSHIP
    #region LOCAL FUNCTIONS
    public function getAbilities(){
    }
    #endregion LOCAL FUNCTIONS
    #region STATIC FUNCTIONS
    public static function findUserByNickname($nickname, $ministry_id){
        if(!$user = UserMinistry::whereMinistryId($ministry_id)
            ->whereNickname($nickname)
            ->first()
        ) return null;
        return $user->user_id;
    }
    #endregion STATIC FUNCTIONS
}