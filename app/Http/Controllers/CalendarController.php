<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

use App\Services\CalendarService;

class CalendarController extends Controller
{
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
}