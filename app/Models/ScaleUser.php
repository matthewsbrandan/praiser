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
        'ability',
    ];

    public function scale(){
        return $this->belongsTo(Scale::class, 'scale_id');
    }
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}