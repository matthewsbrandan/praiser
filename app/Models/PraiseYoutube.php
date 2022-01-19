<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PraiseYoutube extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'praise_id',
    ];

    public function praise(){
        return $this->belongsTo(Praise::class, 'praise_id');
    }
}