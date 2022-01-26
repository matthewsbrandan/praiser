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
}