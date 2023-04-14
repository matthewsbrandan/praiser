<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashController extends Controller
{
  public function index(){
    $launchs = $this->getCashOfMinistry(auth()->user()->current_ministry);
    if(!$launchs) return redirect()->back()->with(
      'notify-type','danger'
    )->with('notify','Este ministério não possui caixa');
    
    $goals = $this->getGoals(auth()->user()->current_ministry);
    $resume = $this->getResume(auth()->user()->current_ministry, $launchs, $goals);

    return view('cash.index', [
      'launchs' => $launchs,
      'goals' => $goals,
      'resume' => $resume
    ]);
  }
  public function getCashOfMinistry($ministry_id){
    /**
     * date_formatted = 'dd/mm/YYYY'
     * type = 'income' | 'expense'
     * value = XX.XX | -XX.XX
     * value_formatted = 'R$ XX,XX' | '-R$ XX,XX'
     * title = 'Contribuição' / 'Compra' / ...
     * description = 'Integrante' / 'Item adiquirido' / ....
     */

    switch($ministry_id){
      case 1: // OFICIAL
        $cash = [];

        $value = 30.00;
        $cash[]= (object)[
          'date_formatted' => '22/01/2023',
          'type' => 'income',
          'value' =>  $value,
          'value_formatted' => self::FormatMoney($value),
          'title' => 'Inicial',
          'description' => 'Caixa Inicial'
        ];
        
        $value = 20.00;
        $cash[]= (object)[
          'date_formatted' => '27/02/2023',
          'type' => 'income',
          'value' => $value,
          'value_formatted' => self::FormatMoney($value),
          'title' => 'Contribuição',
          'description' => 'Sthefany'
        ];

        $value = 20.00;
        $cash[]= (object)[
          'date_formatted' => '09/03/2023',
          'type' => 'income',
          'value' => $value,
          'value_formatted' => self::FormatMoney($value),
          'title' => 'Contribuição',
          'description' => 'Sérgio'
        ];

        $value = 15.00;
        $cash[] = (object)[
          'date_formatted' => '12/04/2023',
          'type' => 'income',
          'value' => $value,
          'value_formatted' => self:FormatMoney($value),
          'title' => 'Contribuição',
          'description' => 'Sérgio'
        ];

        $value = 15.00;
        $cash[] = (object)[
          'date_formatted' => '14/04/2023',
          'type' => 'income',
          'value' => $value,
          'value_formatted' => self::FormatMoney($value),
          'title' => 'Contribuição',
          'description' => 'Mateus'
        ];
        return array_reverse($cash);
      default: return null;
    }
  }
  public function getResume($ministry_id, $launchs = null, $goals = null){
    if(!$launchs) $launchs = $this->getCashOfMinistry($ministry_id);
    if(!$launchs) return null;
    
    if(!$goals) $goals = $this->getGoals($ministry_id);

    $total = array_sum(array_column($launchs, 'value'));
    $valueToGoal = null;
    $parcentToGoal = null;
    if(count($goals) > 0){
      $nextGoal = $goals[0];
      if($nextGoal->value){
        $valueToGoal = $nextGoal->value - $total;
        if($valueToGoal < 0) $valueToGoal = 0; 
  
        $parcentToGoal = round(($total * 100) / $valueToGoal);
      }
    }

    return (object)[
      'total' => $total,
      'total_formatted' => $this->formatMoney($total),
      'value_to_goal' => $valueToGoal,
      'value_to_goal_formatted' => $valueToGoal ? $this->FormatMoney($valueToGoal) : null,
      'percent_to_goal' => (int) $parcentToGoal
    ];
  }
  public function getGoals($ministry_id){
    switch($ministry_id){
      case 1: // OFICIAL
        $goals = [];

        // INSERIR MARCAÇÃO DE OBJETIVOS ALCANÇADOS
        // $value = 1299;
        // $value_max = 2159;
        // $goals[]= (object)[
        //   'title' => "Prato RIDE 20' ou 22' Liga B20",
        //   'image' => "https://http2.mlstatic.com/D_NQ_NP_916810-MLB52353462653_112022-O.webp",
        //   'value' => $value,
        //   'value_max' => $value_max,
        //   'value_formatted' => self::FormatMoney($value),
        //   'value_max_formatted' => self::FormatMoney($value_max),
        //   'links' => [
        //     (object)['name' => "Mercado Livre RIDE 20' (1.299)", 'href' => 'https://produto.mercadolivre.com.br/MLB-2933241182-prato-odery-bronz-performance-series-ride-20-b20-_JM?matt_tool=36625289&matt_word=&matt_source=google&matt_campaign_id=14300459467&matt_ad_group_id=124587331423&matt_match_type=&matt_network=g&matt_device=c&matt_creative=539490865649&matt_keyword=&matt_ad_position=&matt_ad_type=pla&matt_merchant_id=648249889&matt_product_id=MLB2933241182&matt_product_partition_id=1402779707016&matt_target_id=aud-378637574381:pla-1402779707016&gclid=EAIaIQobChMIn9_qqsvJ-wIVPUFIAB0bmgCMEAQYBCABEgKuz_D_BwE'],
        //     (object)['name' => "Youtube (RIDE 20')", 'href' => 'https://www.youtube.com/watch?v=tAmSeL8PVCM'],
        //     (object)['name' => "Mercado Livre RIDE 22' (2.159)", 'href' => 'https://produto.mercadolivre.com.br/MLB-1593746346-prato-bronz-performance-seriesb20-ride-22-polegadas-_JM'],
        //     (object)['name' => "Youtube (RIDE 22')", 'href' => 'https://www.youtube.com/watch?v=oD9MgDJqo0A']
        //   ]
        // ];

        // $value = 719;
        // $value_max = 849;
        // $goals[]= (object)[
        //   'title' => "Prato CRASH 16' ou 17' Liga B20",
        //   'image' => "https://http2.mlstatic.com/D_NQ_NP_968619-MLB44171067956_112020-O.webp",
        //   'value' => $value,
        //   'value_max' => $value_max,
        //   'value_formatted' => self::FormatMoney($value),
        //   'value_max_formatted' => self::FormatMoney($value_max),
        //   'links' => [
        //     (object)['name' => "Mercado Livre CRASH 16' (719)", 'href' => 'https://produto.mercadolivre.com.br/MLB-1726968954-prato-bronz-performance-series-b20-crash-16-polegadas-_JM#position=7&search_layout=grid&type=item&tracking_id=eb97069e-0672-490c-b2b2-39df96b318be'],
        //     (object)['name' => "Youtube (CRASH 16')", 'href' => 'https://www.youtube.com/watch?v=7OfXzYkSR6w'],
        //     (object)['name' => "Mercado Livre CRASH 17' (849)", 'href' => 'https://produto.mercadolivre.com.br/MLB-1667274403-prato-bronz-performance-series-b20-crash-17-polegadas-_JM#position=9&search_layout=grid&type=item&tracking_id=1f353444-101c-4406-8a6a-c508a1310a32'],
        //     (object)['name' => "Youtube (CRASH 17')", 'href' => 'https://www.youtube.com/watch?v=cHDuU9KUY30']
        //   ]
        // ];

        $value = 90;
        $value_max = null;
        $goals[]= (object)[
          'title' => "Manutenção caixa da direita",
          'image' => "https://images.tcdn.com.br/img/img_prod/607502/caixa_de_som_amplificada_300w_rms_ps15auwb_jef_3157_1_ed2468dc9eba41cc7f510b8be6ebf36c.png",
          'on_budget' => true,
          'value' => $value,
          'value_max' => $value_max,
          'value_formatted' => self::FormatMoney($value),
          'value_max_formatted' => self::FormatMoney($value_max)
        ];

        return $goals;
      default: return [];
    }
  }
  #region STATIC FUNCTIONS
  public static function FormatMoney($value){
    if(!$value && $value !== 0) return '??';
    return ($value < 0 ? 
      '-R$ ' . number_format($value * -1, 2 ,',','.'):
      'R$ ' . number_format($value, 2 ,',','.')
    );
  }
  #endregion STATIC FUNCTIONS
}