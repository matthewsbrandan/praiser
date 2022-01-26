<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token'
    ];

    public $increment = false;
    const UPDATED_AT = null;

    public function user(){
        return $this->belongsTo(User::class,'email','email');
    }
}