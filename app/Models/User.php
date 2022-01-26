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
        'profile',
        'google_id',
        'whatsapp',
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
    public function ministries(){
        return $this->belongsToMany(Ministry::class, 'user_ministries', 'user_id', 'ministry_id');
    }
    public function userAbilities(){
        return $this->hasMany(UserAbility::class, 'user_id');
    }
    public function abilities(){
        return $this->belongsToMany(Ability::class, 'user_abilities', 'user_id', 'ability_id');
    }
    public function favoritePraises(){
        return $this->hasMany(FavoritePraise::class, 'user_id');
    }
    #endregion RELATIONSHIP
    #region ACCESS_FUNCTIONS
    public function selectMinistry($ministry_id){
        if(!UserMinistry::whereMinistryId($ministry_id)
            ->whereUserId(auth()->user()->id)
            ->first()
        ) return false; // NÃO FAZ PARTE DO MINISTÉRIO
        $this->update(['current_ministry' => $ministry_id]);
        return true;
    }
    public function outhersAbilities(){
        $myAbilitiesIds = array_column($this->abilities->toArray(), 'id');
        return Ability::whereNotIn('id', $myAbilitiesIds)->get();
    }
    public function adminOnly(){
        return in_array($this->type,['admin','dev']);
    }
    public function devOnly(){
        return $this->type == 'dev';
    }
    public function userMinistryId($ministry_id){
        return $this->userMinistries()->whereMinistryId($ministry_id)->first();
    }
    #region FORMATTE
    public function getProfile(){
        return $this->profile ?? User::getDefaultProfile();
    }
    public function getAvailability(){
        return array_filter(explode(',',$this->availability), function($availability){
            return !!$availability;
        });
    }
    public function getAvailabilityFormatted($weekday){
        return (User::getAvailableWeekdays())[$weekday] ?? null;
    }
    public function getAllCaptions($object = false){
        $captions = [];
        foreach($this->userMinistries as $userMinistry){
            
            foreach($userMinistry->getCaptionFormatted() as $caption){
                if(!in_array($caption,$captions)) $captions[] = $caption;
            }
        }
        if($object){
            $availables = UserMinistry::getAvailableCaptions();
            return array_map(function($caption) use ($availables) {
                return $availables[$caption] ?? null;
            },$captions);
        }
        else return $captions;
    }
    #endregion FORMATTE
    #endregion ACCESS_FUNCTIONS
    #region STATIC FUNCTIONS
    public static function getAvailableWeekdays(){
        return [
            'monday' => 'Segunda-Feira',
            'tuesday' => 'Terça-Feira',
            'wednesday' => 'Quarta-Feira',
            'thursday' => 'Quinta-Feira',
            'friday' => 'Sexta-Feira',
            'saturday' => 'Sábado',
            'sunday' => 'Domingo'
        ];
    }
    public static function getDefaultProfile(){
        return asset('assets/img/profile-default.jpg');
    }
    #endregion STATIC FUNCTIONS
}