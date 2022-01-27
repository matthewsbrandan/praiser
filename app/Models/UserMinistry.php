<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserMinistry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ministry_id',
        'permission',
        'caption',
        'status',
        'nickname',
        'obs'
    ];

    #region RELATIONSHIP
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
    public function ministry(){
        return $this->belongsTo(Ministry::class, 'ministry_id');
    }
    #endregion RELATIONSHIP
    #region LOCAL FUNCTIONS
    public function getCaptionFormatted($object = false){
        $captions = array_filter(explode(',',$this->caption ?? ''), function($caption){
            return !!$caption && strlen($caption) > 0;
        });
        if($object){
            $availables = UserMinistry::getAvailableCaptions();
            return array_map(function($caption) use ($availables) {
                return $availables[$caption] ?? null;
            },$captions);
        }
        return $captions;
    }
    public function getPermissionFormatted($translated = true){
        $permissions = array_filter(explode(',',$this->permission), function ($permission){
            return !!$permission && strlen($permission) > 0;
        });
        if(!$translated) return $permissions;

        $availablePermission = UserMinistry::getAvailablePermissions();

        return array_map(function($permission) use ($availablePermission){
            return $availablePermission[$permission] ?? null;
        },$permissions);
    }
    #endregion LOCAL FUNCTIONS
    #region STATIC FUNCTIONS
    public static function getAvailableCaptions($onlyKeys = false){
        $captions = [
            'lider' => (object)[
                'name' => 'Líder',
                'short' => 'lider',
                'class' => 'bg-danger'
            ],
            'vice' => (object)[
                'name' => 'Vice Líder',
                'short' => 'vice',
                'class' => 'bg-warning'
            ],
            'secratario' => (object)[
                'name' => 'Secratário(a)',
                'short' => 'secratario',
                'class' => 'bg-warning'
            ],
            'musico' => (object)[
                'name' => 'Músico/Musicista',
                'short' => 'musico',
                'class' => 'bg-info'
            ],
            'ministro' => (object)[
                'name' => 'Ministro',
                'short' => 'ministro',
                'class' => 'bg-dark'
            ],
            'backvocal' => (object)[
                'name' => 'Back Vocal',
                'short' => 'backvocal',
                'class' => 'bg-secondary'
            ],
            'mesario' => (object)[
                'name' => 'Mesário',
                'short' => 'mesario',
                'class' => 'bg-light'
            ],
            'datashow' => (object)[
                'name' => 'Datashow',
                'short' => 'datashow',
                'class' => 'bg-light'
            ],
            'danca' => (object)[
                'name' => 'Dança',
                'short' => 'danca',
                'class' => 'bg-success'
            ],
        ];

        return $onlyKeys ? array_keys($captions) : $captions;
    }
    public static function getAvailablePermissions($onlyKeys = false){
        $availablePermission = [
            'can_manage_scale' => 'Gerenciar escalas',
            'can_manage_integrant' => 'Gerenciar integrantes'
        ];
        return $onlyKeys ? array_keys($availablePermission) : $availablePermission;
    }
    #endregion STATIC FUNCTIONS
}