<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoritePraise extends Model
{
    use HasFactory;

    protected $fillable = [
        'praise_id',
        'youtube_link',
        'cipher_link',
        'tone',
    ];

    public function praise(){
        return $this->belongsTo(Praise::class, 'praise_id');
    }
}