<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Exception;

use App\Models\UserAvailability;
use App\Models\UserMinistry;


class UserAvailabilityController extends Controller
{
  /**
   * @description Adicionar usuário a tabela de disponibilidades/indisponibilidades
   * @params
   *  {
   *    "request": {
   *      "type": "Request",
   *      "data": {
   *        "user_id": {
   *          "type": "integer",
   *          "description": "Id do usuário a ser adicionado a tabela"
   *        },
   *        "date": {
   *          "type": "string",
   *          "description": "Data a ser adicionada"
   *        },
   *        "date_final": {
   *          "type": "string",
   *          "description": "Data final. Se esse campo for adicionado indicará, enves de uma data, um período de tempo entre date e date_final",
   *          "nullable": true
   *        },
   *        "is_available": {
   *          "type": "boolean",
   *          "description": "True caso o registro seja de disponibilidade. O padrão é false (indisponibilidade)",
   *          "nullable": true
   *        }
   *      }
   *    }
   *  }
   * @endparams
   */
  public function add(Request $request){
    if(!auth()->user()->current_ministry || !auth()->user()->current_ministry->hasPermissionTo(
      'can_manage_scale'
    )) return $this->jsonOrArray($json, (object)[
      'result' => false,
      'response' => 'Você não está com o ministério selecionado ou não tem permissão para gerir escalas'
    ]);

    if(
      !$request->user_id ||
      !$request->date
    ) return response()->json([
      'result' => false,
      'response' => 'O usuário e a data são obrigatórios'
    ]);

    if(!$userMinistry = UserMinistry::whereMinistryId(
      auth()->user()->current_ministry_id
    )->whereUserId($request->user_id)->first()) return response()->json([
      'result' => false,
      'response' => 'Usuário inválido'
    ]);

    $date = null;
    $date_final = null;
    try{
      $date = Carbon::createFromFormat('Y-m-d', $request->date);
      if($request->date_final) $date_final = Carbon::createFromFormat('Y-m-d', $request->date_final);
    }catch(Exception $e){
      return response()->json([
        'result' => false,
        'response' => 'Houve um erro ao lidar com as datas'
      ]);
    }

    $userAvailability = UserAvailability::updateOrCreate([
      'date' => $date,
      'user_id' => $userMinistry->user_id,
      'ministry_id' => auth()->user()->current_ministry_id,
    ],[
      'date' => $date,
      'date_final' => $date_final,
      'user_id' => $userMinistry->user_id,
      'is_unavailable' => !$request->has('is_available'),
      'ministry_id' => auth()->user()->current_ministry_id,
      'obs' => $request->obs ?? null
    ]);

    return response()->json([
      'result' => true,
      'response' => $userAvailability
    ]);
  }
  /**
   * @description Está função retorna as disponibilidades de membros baseados na data passada por parametro e no ministério atual do usuário logado
   * @params
   *  {
   *    "date": {
   *      "type": "string",
   *      "description": "Data no formato yyyy-mm-dd"
   *    }
   *  }
   * @endparams
   * @return
   *  {
   *    "success": {
   *      "type": "JSON",
   *      "description": "Retorna no padrão result e response, com result true e response com o array de usuarios/disponibilidades"
   *    }
   *  }
   * @endreturn
   */
  public function get($date, $json = true){
    if(!auth()->user()->current_ministry || !auth()->user()->current_ministry->hasPermissionTo(
      'can_manage_scale'
    )) return $this->jsonOrArray($json, (object)[
      'result' => false,
      'response' => 'Você não está com o ministério selecionado ou não tem permissão para gerir escalas'
    ]);

    $userAvailables = UserAvailability::whereMinistryId(
      auth()->user()->current_ministry_id
    )->where(function($query) use ($date){
      return $query->whereDate('date', $date)->orWhere(function($condition) use ($date){
        return $condition->whereDate(
          'date', '>=', $date
        )->whereDate(
          'date_final','<=', $date
        );
      });
    })->get();

    $data = (object)[
      'result' => true,
      'response' => $userAvailables
    ];    
    return $this->jsonOrArray($json, $data);
  }
}