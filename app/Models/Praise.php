<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Praise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'singer',
        'tags',
    ];

    #region RELATIONSHIP
    public function youtubes(){
        return $this->hasMany(PraiseYoutube::class, 'praise_id');
    }
    public function ciphers(){
        return $this->hasMany(PraiseCipher::class, 'praise_id');
    }
    #endregion RELATIONSHIP
    #region LOCAL FUNCTIONS
    public function getTagsFormatted(){
        return explode(',',$this->tags ?? '');
    }
    #endregion LOCAL FUNCTIONS
    #region STATIC FUNCTIONS
    public static function getAvailableTags(){
        $tags = ['adoracao','agitado','congregacional','fogo'];
        return $tags;
    }
    #endregion STATIC FUNCTIONS
}