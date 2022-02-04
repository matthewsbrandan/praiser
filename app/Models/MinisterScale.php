<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MinisterScale extends Model
{
    use HasFactory;

    protected $fillable = [
        'scale_id', /*se a escala de louvores não tiver scale_id é uma escala de rascunho*/
        'user_id',
        'verse',
        'about',
        'privacy',
        'playlist',
        'ministry_id'
    ];

    public function scale(){
        return $this->belongsTo(Scale::class, 'scale_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function scale_praises(){
        return $this->hasMany(ScalePraise::class, 'minister_scale_id');
    }
    public function ministry(){
        return $this->belongsTo(Ministry::class, 'ministry_id');
    }
}