<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotionPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'title',
        'description',
        'wallpaper',
        'tags',
        'ministry_id'
    ];

    #region RELATIONSHIP
    public function ministry(){
        return $this->belongsTo(Ministry::class, 'ministry_id');
    }
    #endregion RELATIONSHIP
}