<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use Exception;

use App\Services\CalendarService;

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
    if($unpermission = $this->verifyPermission(true)) return $unpermission;

    if(
      !$request->user_id ||
      !$request->date
    ) return response()->json([
      'result' => false,
      'response' => 'O usuário e a data são obrigatórios'
    ]);

    if(!$userMinistry = UserMinistry::whereMinistryId(
      auth()->user()->current_ministry
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
      'ministry_id' => auth()->user()->current_ministry,
    ],[
      'date' => $date,
      'date_final' => $date_final,
      'user_id' => $userMinistry->user_id,
      'is_unavailable' => !$request->has('is_available'),
      'ministry_id' => auth()->user()->current_ministry,
      'obs' => $request->obs ?? null
    ]);

    $data = $this->get($date, false);
    if(!$data->result) return response()->json([
      'result' => false,
      'response' => 'Informação salva, porém houve um atualizar os dados do dia'
    ]);

    return response()->json($data);
  }
  public function remove(Request $request){
    if($unpermission = $this->verifyPermission(true)) return $unpermission;
    if(
      !$request->id ||
      !$request->user_id
    ) return response()->json([
      'result' => false,
      'response' => 'A data, id da informação e id do usuário são obrigatórios'
    ]);
    
    if(!$userAv = UserAvailability::whereMinistryId(auth()->user()->current_ministry)
      ->whereId($request->id)
      ->whereUserId($request->user_id)
      ->first()
    ) return response()->json([
      'result' => false,
      'response' => 'Registro não encontrado'
    ]);

    $userAv->delete();
    return response()->json([
      'result' => true,
      'response' => 'Excluído com sucesso'
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
    if($unpermission = $this->verifyPermission($json)) return $unpermission;

    $userAvailables = UserAvailability::whereMinistryId(
      auth()->user()->current_ministry
    )->where(function($query) use ($date){
      return $query->whereDate('date', $date)->orWhere(function($condition) use ($date){
        return $condition->whereDate(
          'date', '<=', $date
        )->whereDate(
          'date_final','>=', $date
        );
      });
    })->get()->map(function($userAv){
      $user = $userAv->user()->first();
      $userMin = $user->userMinistries()->whereMinistryId(auth()->user()->current_ministry)->first();
      
      $userAv->name = $userMin->nickname ?? $user->name;
      $userAv->profile = $user->getProfile();

      return $userAv;
    });

    $data = (object)[
      'result' => true,
      'response' => $userAvailables
    ];    
    return $this->jsonOrArray($json, $data);
  }
  public function getByUser($user_id, $date_start, $date_end, $json = true){
    if($unpermission = $this->verifyPermission($json)) return $unpermission;

    $userAvailables = UserAvailability::whereUserId($user_id)->whereMinistryId(
      auth()->user()->current_ministry
    )->whereDate('date', '<=', $date_start)->whereDate('date_final','>=', $date_end)->get();

    $data = (object)[
      'result' => true,
      'response' => $userAvailables
    ];    
    return $this->jsonOrArray($json, $data);
  }
  public function getMonth($date = null, $json = true){
    if(!$date) $date = Carbon::now();
    else $date = Carbon::createFromFormat('d-m-Y', "01-".$date);
    $neutro = Carbon::createFromFormat('Y-m-d',$date->format('Y-m-d'))->startOfMonth();
    $prevMonth = Carbon::createFromFormat('Y-m-d',$neutro->format('Y-m-d'))->subMonth();
    $nextMonth = Carbon::createFromFormat('Y-m-d',$neutro->format('Y-m-d'))->addMonth();
    $link = (object) [
      'prev' => route('calendar.month', ['date' => $prevMonth->format('m-Y')]),
      'next' => route('calendar.month', ['date' => $nextMonth->format('m-Y')]),
    ];

    $service = new CalendarService($date);
    [$calendar,$month_name] = $service->getMonth();

    $calendar = array_map(function($day){
      $day->day = $day->date->format('d');
      $day->date_Ymd = $day->date->format('Y-m-d');
      $day->data = collect([]);

      $data = $this->get($day->date_Ymd, false);
      if($data->result) $day->data = $data->response;

      return $day;
    }, $calendar);

    $data = [
      'result' => true,
      'response' => [
        'calendar' => $calendar,
        'month_name' => $month_name,
        'link' => $link
      ]
    ];
    return $json ? response()->json([
      'result' => true,
      'response' => [
        'calendar' => $calendar,
        'month_name' => $month_name,
        'link' => $link
      ]
    ]) : (object) $data;
  }
  #region PROTECTED FUNCTIONS
  protected function verifyPermission($json){
    if(!auth()->user()->currentMinistry || !auth()->user()->currentMinistry->hasPermissionTo(
      'can_manage_scale'
    )) return $this->jsonOrArray($json, (object)[
      'result' => false,
      'response' => 'Você não está com o ministério selecionado ou não tem permissão para gerir escalas'
    ]);
    return null;
  }
  #endregion PROTECTED FUNCTIONS
}