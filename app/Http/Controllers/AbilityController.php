<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ability;
use App\Models\UserAbility;

class AbilityController extends Controller
{
    public function bind(Request $request){
        foreach(auth()->user()->userAbilities as $ability){
            if(!in_array($ability->ability_id, $request->abilities)){
                $ability->delete();
            }
        }
        foreach($request->abilities as $abilities){
            if(Ability::whereId($abilities)){
                $data = [
                    'user_id' => auth()->user()->id,
                    'ability_id' => $abilities
                ];
                UserAbility::updateOrCreate($data,$data);
            }
        }
        return redirect()->route('user.profile')->with(
            'message',
            'Habilidades Editadas'
        );
    }
    public function search($ability, $weekday = null, $json = true){
        if(!$ability = Ability::whereSlug($ability)->first()){
            $data = [
                'result' => false,
                'response' => 'Habilidade não encontrada'
            ];
            return $json ? response()->json($data) : (object) $data;
        }
        $users = UserAbility::join('user_ministries', 'user_abilities.user_id', '=', 'user_ministries.user_id')
            ->where('user_ministries.ministry_id', auth()->user()->current_ministry)
            ->where('user_abilities.ability_id', $ability->id)
            ->get();

        $users = $users->map(function($user) use ($weekday){
            $user->name = $user->user->name;
            $user->profile_formatted = $user->user->getProfile();
            $user->email = $user->user->email;
            $user->availability = $user->user->getAvailability();
            $user->availability_formatted = array_map(function($availability) use ($user){
                return $user->user->getAvailabilityFormatted($availability);
            }, $user->availability);
            $user->outhers_availability = $user->user->outhers_availability;
            $user->has_availability = $user->user->hasAvailability($weekday);

            return $user;
        });

        $sortedUser = collect([]);
        if($weekday) foreach($users->sortByDesc('has_availability')->map(function($user){ return $user; }) as $user){
            $sortedUser->push($user);
        }
        else $sortedUser = $users;

        $data = [
            'result' => true,
            'response' => $sortedUser
        ];
        return $json ? response()->json($data) : (object) $data;
    }
}