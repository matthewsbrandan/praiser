<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ministry;
use App\Models\UserAbility;
use App\Models\UserMinistry;

class UserMinistryController extends Controller
{
    public function update(Request $request){
        if(!$ministry = Ministry::whereId($request->ministry_id)->first()) return redirect()->back()->with(
            'message',
            'Ministério não encontrado'
        );
        if(!$ministry->hasPermissionTo('can_manage_integrant')) return redirect()->back()->with(
            'message',
            'Você não tem permissão para gerenciar os integrantes desse ministério'
        );
        if(!$userMinistry = UserMinistry::whereMinistryId($request->ministry_id)
            ->whereUserId($request->user_id)
            ->first()
        ) return redirect()->back()->with(
            'message',
            'Integrante não localizado'
        );
        $userMinistry->update(['caption'=> $request->caption]);
        
        $userAbility = UserAbility::whereUserId($request->user_id)->whereIn('id',array_keys($request->exp))->get();


        foreach($userAbility as $ability){
            if($request->exp[$ability->id]) $ability->update([
                'exp' => $request->exp[$ability->id] == 0 ? null : $request->exp[$ability->id]
            ]);
        }

        return redirect()->route('user.profile',['email' => $userMinistry->user->email])->with(
            'message',
            'Títulos e habilidades alterados com sucesso'
        );
    }
}