<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PraiseCipher extends Model
{
    use HasFactory;

    protected $fillable = [
        'link',
        'original_tone',
        'praise_id'
    ];

    public function praise(){
        return $this->belongsTo(Praise::class, 'praise_id');
    }
}