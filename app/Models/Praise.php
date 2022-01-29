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
        'ministry_id'
    ];

    #region RELATIONSHIP
    public function youtubes(){
        return $this->hasMany(PraiseYoutube::class, 'praise_id');
    }
    public function ciphers(){
        return $this->hasMany(PraiseCipher::class, 'praise_id');
    }
    public function favorites(){
        return $this->hasMany(FavoritePraise::class, 'praise_id');
    }
    public function ministry(){
        return $this->belongsTo(Ministry::class, 'ministry_id');
    }
    #endregion RELATIONSHIP
    #region ACCESS FUNCTIONS
    public function mainYoutube(){
        return $this->youtubes()->orderBy('updated_at','desc')->first();
    }
    public function mainCipher(){
        return $this->ciphers()->orderBy('updated_at','desc')->first();
    }
    public function hasReference(){
        return $this->mainYoutube() || $this->mainCipher();
    }
    public function favorite(){
        return $this->favorites()->whereUserId(auth()->user()->id)->first();
    }
    #endredion ACCESS FUNCTIONS
    #region LOCAL FUNCTIONS
    public function getTagsFormatted(){
        return explode(',',$this->tags ?? '');
    }
    public function getHashtagsFormatted(){
        return $this->tags ? array_map(function($tag){
            return '#'.$tag;
        }, $this->getTagsFormatted()) : [];
    }
    #endregion LOCAL FUNCTIONS
    #region STATIC FUNCTIONS
    public static function getAvailableTags(){
        $tags = ['adoracao','agitado','congregacional','fogo','espiritual','oferta','agradecimento'];
        return $tags;
    }
    #endregion STATIC FUNCTIONS
}