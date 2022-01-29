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
        'theme',
        'obs',
    ];
    
    protected $date = ['date'];

    #region RELATIONSHIP
    public function ministry(){
        return $this->belongsTo(Ministry::class, 'ministry_id');
    }
    public function scaleUsers(){
        return $this->hasMany(ScaleUser::class, 'scale_id');
    }
    #endregion RELATIONSHIP
    #region LOCAL FUNCTIONS
    public function myScale(){
        return $this->scaleUsers()->whereUserId(auth()->user()->id)->first();
    }
    public function getResume(){
        $resume = [];
        foreach($this->scaleUsers as $user){
            foreach(explode(',',$user->ability) as $ability){
                $index = array_search($ability, array_column($resume,'ability'));
                if($index === false) $resume[]=[
                    'ability' => $ability,
                    'users' => [$user->nickname]
                ];
                else $resume[$index]['users'][] = $user->nickname;
            }
        }
        return $resume;
    }
    public function getResumeTable($resume = null) {
        if(!$resume) $resume = $this->getResume();
        $table = [
            'ministro' => '-',
            'backvocal' => '-',
            'violao' => '-',
            'baixo' => '-',
            'guitarra' => '-',
            'teclado' => '-',
            'bateria' => '-',
            'cajon' => '-',
            'datashow' => '-',
            'mesario' => '-',
        ];
        foreach($resume as $user){
            $key = $user['ability'] == 'back-vocal' ? 'backvocal' : $user['ability'];
            $table[$key] = implode(', ',$user['users']);
        }
        return $table;
    }
    #endregion LOCAL FUNCTIONS
    #region STATIC FUNCTIONS
    public static function getAvailableHoursByWeekday($weekday){
        $hours = [
            'sunday' => '18:00',
            'monday' => '-',
            'tuesday' => '19:45',
            'wednesday' => '-',
            'thursday' => '19:45',
            'friday' => '-',
            'saturday' => '19:30',
        ];
        return $hours[$weekday] ?? '-';
    }
    public static function getWeekdayByIndex($index){
        $weekdays = ['sunday','monday','tuesday','wednesday','thursday','friday','saturday'];
        return $weekdays[$index] ?? null;
    }
    #endregion STATIC FUNCTIONS
}