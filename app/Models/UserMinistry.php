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
        'caption',
        'status',
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
    public function getCaptionFormatted(){
        return explode(',',$this->caption ?? '');
    }
    #endregion LOCAL FUNCTIONS
    #region STATIC FUNCTIONS
    public static function getAvailableCaptions($onlyKeys = false){
        $captions = [
            'leader' => (object)[
                'name' => 'Líder',
                'short' => 'líder',
                'class' => 'badge-danger'
            ],
            'vice_leader' => (object)[
                'name' => 'Vice Líder',
                'short' => 'vice',
                'class' => 'badge-warning'
            ],
            'secretary' => (object)[
                'name' => 'Secratário(a)',
                'short' => 'secratário',
                'class' => 'badge-warning'
            ],
            'musician' => (object)[
                'name' => 'Músico/Musicista',
                'short' => 'musico',
                'class' => 'badge-info'
            ],
            'minister' => (object)[
                'name' => 'Ministro',
                'short' => 'ministro',
                'class' => 'badge-dark'
            ],
            'backvocal' => (object)[
                'name' => 'Back Vocal',
                'short' => 'Back Vocal',
                'class' => 'badge-secondary'
            ],
            'table' => (object)[
                'name' => 'Mesário',
                'short' => 'Mesário',
                'class' => 'badge-light'
            ],
            'datashow' => (object)[
                'name' => 'Datashow',
                'short' => 'Datashow',
                'class' => 'badge-light'
            ],
            'dance' => (object)[
                'name' => 'Dança',
                'short' => 'Dança',
                'class' => 'badge-success'
            ],
        ];

        return $onlyKeys ? array_keys($captions) : $captions;
    }
    #endregion STATIC FUNCTIONS
}