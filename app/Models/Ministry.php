<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ministry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'image',
        'description',
        'slug',
        'free_entry',
        'user_id'
    ];

    #region RELATIONSHIP
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function userMinistry(){
        return $this->hasMany(UserMinistry::class, 'ministry_id');
    }
    public function users(){
        return $this->belongsToMany(User::class, 'user_ministries', 'ministry_id', 'user_id');
    }

    #endregion RELATIONSHIP
    #region FORMATTE
    public function getImage(){
        return $this->image ?? asset('assets/img/ministry-default.jpg');
    }
    #endregion FORMATTE
    #region ACCESS_FUNCTIONS
    public function my_status(){
        $status = $this->userMinistry->where('user_id', auth()->user()->id)->first();
        return $status->status;
    }
    public function hasPermissionTo($permission){
        if($this->user_id == auth()->user()->id) return true;
        if(!$user = $this->userMinistry->where('user_id', auth()->user()->id)->first()) return false;
        return in_array($permission, $user->getPermissionFormatted());
    }    
    #endregion ACCESS_FUNCTIONS

}