<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scale extends Model
{
    use HasFactory;

    protected $fillable = [
        'ministry_id',
        'date',
        'weekday',
        'hour',
        'obs',
    ];
    
    protected $date = ['date'];

    public function ministry(){
        return $this->belongsTo(Ministry::class, 'ministry_id');
    }
}