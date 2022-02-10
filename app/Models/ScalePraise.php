<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScalePraise extends Model
{
    use HasFactory;

    protected $fillable = [
        'youtube_link',
        'cipher_link',
        'tone',
        'legend',
        'index',
        'praise_id',
        'user_id',
        'minister_scale_id',
    ];

    public function praise(){
        return $this->belongsTo(Praise::class, 'praise_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function ministerScale(){
        return $this->belongsTo(MinisterScale::class, 'minister_scale_id');
    }
}