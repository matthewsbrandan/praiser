<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Exception;
use Carbon\Carbon;

use App\Models\User;
use App\Models\Scale;
use App\Models\ScaleUser;

use App\Services\OfficeService;
use App\Services\CalendarService;

class ScaleController extends Controller
{
    public function week($date = null){
        if(!$date) $date = Carbon::now();
        else $date = Carbon::createFromFormat('d-m-Y', $date);
        $prevWeek = Carbon::createFromFormat('Y-m-d',$date->format('Y-m-d'))->subDays(7);
        $nextWeek = Carbon::createFromFormat('Y-m-d',$date->format('Y-m-d'))->addDays(7);
        $link = (object) [
            'prev' => $prevWeek->format('d-m-Y'),
            'next' => $nextWeek->format('d-m-Y'),
        ];

        $service = new CalendarService($date);
        [$calendar, $week_name] = $service->getWeek();
        $table = collect([]);
        foreach($calendar as &$day){
            $scales = Scale::whereMinistryId(auth()->user()->current_ministry)
                ->whereDate('date',$day->date)
                ->get();
            $day->scales = $scales->map(function($scale){
                $scale->weekday_name = User::getAvailableWeekdays($scale->weekday);
                $scale->resume = $scale->getResume();
                $scale->resume_table = $scale->getResumeTable($scale->resume);
                $arrDate = explode('-',$scale->date);
                $scale->day = count($arrDate) == 3 ? $arrDate[2] : $scale->date;
                return $scale;
            });

            $table = collect([
                ...$table,
                ...$day->scales
            ]);
        }

        return view('scale.week',[
            'calendar' => $calendar,
            'week_name' => $week_name,
            'table' => $table,
            'link' => $link
        ]);
    }
    public function month($date = null){
        if(!$date) $date = Carbon::now();
        else $date = Carbon::createFromFormat('d-m-Y', "01-".$date);
        $neutro = Carbon::createFromFormat('Y-m-d',$date->format('Y-m-d'))->startOfMonth();
        $prevMonth = Carbon::createFromFormat('Y-m-d',$neutro->format('Y-m-d'))->subMonth();
        $nextMonth = Carbon::createFromFormat('Y-m-d',$neutro->format('Y-m-d'))->addMonth();
        $link = (object) [
            'prev' => $prevMonth->format('m-Y'),
            'next' => $nextMonth->format('m-Y'),
        ];

        $service = new CalendarService($date);
        [$calendar,$month_name] = $service->getMonth();
        $table = collect([]);
        foreach($calendar as &$day){
            $scales = Scale::whereMinistryId(auth()->user()->current_ministry)
                ->whereDate('date',$day->date)
                ->get();

            $day->scales = $scales->map(function($scale){
                $scale->weekday_name = User::getAvailableWeekdays($scale->weekday);
                $scale->resume = $scale->getResume();
                $scale->resume_table = $scale->getResumeTable($scale->resume);
                $arrDate = explode('-',$scale->date);
                $scale->day = count($arrDate) == 3 ? $arrDate[2] : $scale->date;
                return $scale;
            });

            $table = collect([
                ...$table,
                ...$day->scales
            ]);
        }

        return view('scale.month',[
            'calendar' => $calendar,
            'month_name' => $month_name,
            'table' => $table,
            'link' => $link
        ]);
    }
    public function create($import = null){
        return view('scale.create',['import' => $import]);
    }
    public function store(Request $request){
        if($request->file('import')){
            return $this->handleImport($request->file('import'));
        }
        dd($request->all());
    }
    protected function handleImport($file){
        $sheet = new OfficeService($file->getRealPath());
        $scales = $sheet->loadScale();
        $errors = [];
        $countSuccess = 0;
        foreach($scales as $scale){
            $weekday = Scale::getWeekdayByIndex($scale['date']->dayOfWeek);
            $hour = Scale::getAvailableHoursByWeekday($weekday);
            $data = [
                'ministry_id' => auth()->user()->current_ministry,
                'date' => $scale['date'],
                'weekday' => $weekday,
                'hour' => $hour,
                'theme' => $scale['theme'],
            ];

            try{
                if($realScale = Scale::create($data)){
                    foreach($scale['scaled'] as $scaled){
                        try{
                            $nickname = $scaled['user'];
                            $user_id = ScaleUser::findUserByNickname(
                                $nickname,
                                auth()->user()->current_ministry
                            );
                            $data = [
                                'scale_id' => $realScale->id,
                                'user_id' => $user_id,
                                'nickname' => $nickname,
                                'ability' => implode(',',$scaled['abilities']),
                            ];
                            ScaleUser::create($data);
                        }catch(Exception $e){
                            $errors[] = "Houve um erro ao escalar ".$scaled['user']." no dia ".$scale['date']->format('d/m/Y');
                        }
                    }
                    $countSuccess++;
                }else{
                    $errors[]= "Não foi possível criar a escala do dia ".$scale['date']->format('d/m/Y');
                }
            }catch(Exception $e){
                $errors[] = "Houve um erro ao criar a escala do dia ".$scale['date']->format('d/m/Y');
            }
        }
        if($countSuccess == 0 && count($errors)) $message = "Nenhuma escala foi importada";
        else{
            $message = "";
            if($countSuccess > 0){
                $message.= $countSuccess == 1 ? "1 escala importada com sucesso<br/>":"$countSuccess escalas importadas com sucesso<br/>";
            }
            if(count($errors) > 0){
                $message.=  count($errors) == 1 ? "1 escala não pode ser importada<br/>":count($errors)." escalas não puderam ser importadas:<br/>";

                $listErrors = array_map(function($error){
                    return "<pre>".json_encode($error)."</pre><hr/>";
                }, $errors);

                $message.= implode('<br/>',$listErrors);
            }
        }
        return redirect()->route('scale.month')->with('message',$message);
    }
}