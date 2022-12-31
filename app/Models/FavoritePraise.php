<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoritePraise extends Model
{
    use HasFactory;

    protected $fillable = [
        'praise_id',
        'user_id',
        'youtube_link',
        'cipher_link',
        'tone',
    ];

    #region RELATIONSHIP
    public function praise(){
        return $this->belongsTo(Praise::class, 'praise_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    #endregion RELATIONSHIP
    #region ACCESS FUNCTIONS
    public function mainYoutube(){
        return $this->youtube_link ? 
            (object)['link' => $this->youtube_link] :
            $this->praise->youtubes()->orderBy('updated_at','desc')->first();
    }
    public function mainCipher(){
        return $this->cipher_link ? 
            (object)['link' => $this->cipher_link] :
            $this->praise->ciphers()->orderBy('updated_at','desc')->first();
    }
    public function hasReference(){
        return $this->youtube_link || $this->cipher_link || $this->praise->hasReference();
    }
    #endregion ACCESS FUNCTIONS
}