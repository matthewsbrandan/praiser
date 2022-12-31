<?php

namespace App\Services;

use Carbon\Carbon;
use Exception;

use App\Models\Scale;

class CalendarService{
  protected $date;
  protected $month_name;
  public function __construct($date){
    $this->date = $date;
    $this->month_name = $this->getMonthName((int) $date->format('m'));
  }
  public function getMonth(){
    $startMonth = Carbon::createFromFormat('Y-m-d',$this->date->startOfMonth()->format('Y-m-d'));
    $firstDayOfWeek = $startMonth->dayOfWeek;
    $calendar = [];
    if($firstDayOfWeek != 1){
      $temp = [];
      $firstDayOfWeek = $firstDayOfWeek == 0 ? 6 : $firstDayOfWeek - 1;
      for($i = $firstDayOfWeek; $i > 0; $i--){
        $temp[]= $this->formatteDateToCalendar($startMonth->subDay());
      }
      $calendar = [...array_reverse($temp)];
    }
    $startMonth = Carbon::createFromFormat('Y-m-d',$this->date->startOfMonth()->format('Y-m-d'));
    $calendar[]= $this->formatteDateToCalendar($startMonth);
    $lastDay = (int) $this->date->endOfMonth()->format('d');
    for($i = 1; $i < $lastDay; $i++){
      $calendar[]= $this->formatteDateToCalendar($startMonth->addDay());
    }
    $lasDayOfWeek = $startMonth->dayOfWeek;
    for($i = $lasDayOfWeek; $i < 7; $i++){
      $calendar[]= $this->formatteDateToCalendar($startMonth->addDay());
    }
    return [$calendar, $this->month_name];
  }
  public function getWeek(){
    $firstDayOfWeek = Carbon::createFromFormat('Y-m-d',$this->date->format('Y-m-d'));
    $currentWeekday = $this->date->dayOfWeek;
    if($currentWeekday != 1){
      $firstDayOfWeek->subDays(
        $currentWeekday == 0 ? 6 : $currentWeekday - 1
      );
    }
    $week_name = $firstDayOfWeek->format('d/m');
    $calendar = [$this->formatteDateToCalendar($firstDayOfWeek)];
    for($i = 0; $i < 6; $i++){
      $calendar[]= $this->formatteDateToCalendar($firstDayOfWeek->addDay());
    }
    $week_name.= " - ".$firstDayOfWeek->format('d/m');
    return [$calendar, $week_name];
  }
  protected function getMonthName($month){
    $month_names = [
      'Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'
    ];
    return $month_names[$month - 1] ?? null;
  }
  protected function formatteDateToCalendar($date){
    $diff = $date->diffInDays(null, false);
    return (object)[
      'date' => Carbon::createFromFormat('Y-m-d',$date->format('Y-m-d')),
      'date_formatted' => $date->format('d/m/Y'),
      'weekday' => Scale::getWeekdayByIndex($date->dayOfWeek),
      'weekday_index' => $date->dayOfWeek,
      'is_current_month' => $this->date->format('m') == $date->format('m'),
      '_time' => $diff == 0 ? 'present' : ($diff < 0 ? 'future':'past')
    ];
  }
}