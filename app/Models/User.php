<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'whatsapp',
        'permission',
        'type',
        'current_ministry',
        'availability',
        'outhers_availability'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    #region RELATIONSHIP
    public function currentMinistry(){
        return $this->belongsTo(Ministry::class, 'current_ministry');
    }
    public function userMinistries(){
        return $this->hasMany(UserMinistry::class, 'user_id');
    }
    #endregion RELATIONSHIP
    #region LOCAL FUNCTIONS
    public function getPermissionFormatted($translated = true){
        $permissions = explode(',',$this->permission);
        if(!$translated) return $permissions;

        $availablePermission = User::getAvailablePermissions();


        return array_map(function($permission) use ($availablePermission){
            return $availablePermission[$permission] ?? null;
        },$permissions);
    }
    #endregion LOCAL FUNCTIONS
    #region STATIC FUNCTIONS
    public static function getAvailablePermissions($onlyKeys = false){
        $availablePermission = [
            'can_manage_scale' => 'Gerenciar escalas'
        ];
        return $onlyKeys ? array_keys($availablePermission) : $availablePermission;
    }
    #endregion STATIC FUNCTIONS
}