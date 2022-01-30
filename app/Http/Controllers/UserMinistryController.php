<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Ministry;
use App\Models\ScaleUser;
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

        $oldNickname = $userMinistry->nickname;
        $userMinistry->update([
            'caption'=> $request->caption,
            'nickname' => $request->nickname
        ]);
        if($oldNickname !== $request->nickname) $this->handleChangeNickname($userMinistry);

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
    public function handleChangeNickname($userMinistry){
        ScaleUser::join('scales', 'scale_users.scale_id', '=', 'scales.id')
            ->where('scales.ministry_id',$userMinistry->ministry_id)
            ->whereNull('scale_users.user_id')
            ->where('scale_users.nickname', $userMinistry->nickname)
            ->update(['user_id' => $userMinistry->user_id]);
    }
    public function remove($ministry_id, $user_id){
        if(!$ministry = Ministry::whereId($ministry_id)->first()) return redirect()->back()->with(
            'message',
            'Ministério não encontrado'
        );
        if(auth()->user()->id === $user_id || $ministry->hasPermissionTo('can_manage_integrant')){
            if(!$user_ministry = UserMinistry::whereMinistryId($ministry_id)
                ->whereUserId($user_id)
                ->first()
            ) return redirect()->back()->with(
                'message',
                'Membro não localizado'
            );
            $user_ministry->delete();
            return redirect()->route('ministry.index',['slug' => $ministry->slug])->with(
                'message',
                'Membro removido com sucesso'
            );
        }
        return redirect()->back()->with(
            'message',
            'Você não tem permissão para realizar está ação'
        );
    }
}