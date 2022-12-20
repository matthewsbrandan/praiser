<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Carbon\Carbon;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public static function getVerses($random = false){
    $verse = [
      (object)[
        "text" => "Entrem por suas portas com ações de graças e em seus átrios com louvor; deem-lhe graças e bendigam o seu nome.",
        "ref" => "Salmos 100:4"
      ],
      (object)[
        "text" => "Senhor, quero dar-te graças de todo o coração e falar de todas as tuas maravilhas. Em ti quero alegrar-me e exultar, e cantar louvores ao teu nome, ó Altíssimo.",
        "ref" => "Salmos 9:1-2"
      ],    
      (object)[
        "text" => "Por meio de Jesus, portanto, ofereçamos continuamente a Deus um sacrifício de louvor, que é fruto de lábios que confessam o seu nome.",
        "ref" => "Hebreus 13:15"
      ],    
      (object)[
        "text" => "Seja ele o motivo do seu louvor, pois ele é o seu Deus, que por vocês fez aquelas grandes e temíveis maravilhas que vocês viram com os próprios olhos.",
        "ref" => "Deuteronômio 10:21"
      ],    
      (object)[
        "text" => "Vocês, porém, são geração eleita, sacerdócio real, nação santa, povo exclusivo de Deus, para anunciar as grandezas daquele que os chamou das trevas para a sua maravilhosa luz.",
        "ref" => "1 Pedro 2:9"
      ],    
      (object)[
        "text" => "Então veio do trono uma voz, conclamando: 'Louvem o nosso Deus, todos vocês, seus servos, vocês que o temem, tanto pequenos como grandes!'",
        "ref" => "Apocalipse 19:5"
      ],    
      (object)[
        "text" => "Bendiga o Senhor a minha alma! Não esqueça nenhuma de suas bênçãos!",
        "ref" => "Salmos 103:2"
      ],    
      (object)[
        "text" => "Que eles deem graças ao Senhor por seu amor leal e por suas maravilhas em favor dos homens, porque ele sacia o sedento e satisfaz plenamente o faminto.",
        "ref" => "Salmos 107:8-9"
      ],    
      (object)[
        "text" => "Mudaste o meu pranto em dança, a minha veste de lamento em veste de alegria, para que o meu coração cante louvores a ti e não se cale. Senhor, meu Deus, eu te darei graças para sempre.",
        "ref" => "Salmos 30:11-12"
      ],    
      (object)[
        "text" => "Cantem de alegria ao Senhor, vocês que são justos; aos que são retos fica bem louvá-lo. Louvem o Senhor com harpa; ofereçam-lhe música com lira de dez cordas. Cantem-lhe uma nova canção; toquem com habilidade ao aclamá-lo.",
        "ref" => "Salmos 33:1-3"
      ],    
      (object)[
        "text" => "Bendirei o Senhor o tempo todo! Os meus lábios sempre o louvarão. Minha alma se gloriará no Senhor; ouçam os oprimidos e se alegrem. Proclamem a grandeza do Senhor comigo; juntos exaltemos o seu nome.",
        "ref" => "Salmos 34:1-3"
      ],    
      (object)[
        "text" => "O louvor te aguarda em Sião, ó Deus; os votos que te fizemos serão cumpridos. Ó tu que ouves a oração, a ti virão todos os homens. Quando os nossos pecados pesavam sobre nós, tu mesmo fizeste propiciação por nossas transgressões.",
        "ref" => "Salmos 65:1-3"
      ],    
      (object)[
        "text" => "Louvem eles o seu nome com danças; ofereçam-lhe música com tamborim e harpa.",
        "ref" => "Salmos 149:3"
      ],    
      (object)[
        "text" => "Aleluia! Como é bom cantar louvores ao nosso Deus! Como é agradável e próprio louvá-lo!",
        "ref" => "Salmos 147:1"
      ],    
      (object)[
        "text" => "Aleluia! Louvem o Senhor desde os céus, louvem-no nas alturas! Louvem-no todos os seus anjos, louvem-no todos os seus exércitos celestiais. Louvem-no sol e lua, louvem-no todas as estrelas cintilantes. Louvem-no os mais altos céus e as águas acima do firmamento. Louvem todos eles o nome do Senhor, pois ordenou, e eles foram criados. Ele os estabeleceu em seus lugares para todo o sempre; deu-lhes um decreto que jamais mudará. Louvem o Senhor, vocês que estão na terra, serpentes marinhas e todas as profundezas, relâmpagos e granizo, neve e neblina, vendavais que cumprem o que ele determina, todas as montanhas e colinas, árvores frutíferas e todos os cedros, todos os animais selvagens e os rebanhos domésticos, todos os demais seres vivos e as aves, reis da terra e todas as nações, todos os governantes e juízes da terra, moços e moças, velhos e crianças. Louvem todos o nome do Senhor, pois somente o seu nome é exaltado; a sua majestade está acima da terra e dos céus.",
        "ref" => "Salmos 148:1-13"
      ],
      (object)[
        "text" => "Por isso te louvarei entre as nações, ó Senhor; cantarei louvores ao teu nome.",
        "ref" => "2 Samuel 22:50"
      ]
    ];
    if($random) return $verse[random_int(0, count($verse) - 1)];
    return $verse;
  }

  public function policy(){
    return view('policy');
  }
  public function terms(){
    return view('terms');
  }
  #region LOCAL FUNCTIONS
  protected function generateSlug($str, $separator = '-'){
    $str = mb_strtolower($str);
    $str = preg_replace('/(â|á|ã)/', 'a', $str);
    $str = preg_replace('/(ê|é)/', 'e', $str);
    $str = preg_replace('/(í|Í)/', 'i', $str);
    $str = preg_replace('/(ú)/', 'u', $str);
    $str = preg_replace('/(ó|ô|õ|Ô)/', 'o',$str);
    $str = preg_replace('/(_|\/|!|\?|#)/', '',$str);
    $str = preg_replace('/( )/', $separator ,$str);
    $str = preg_replace('/ç/','c',$str);
    $str = preg_replace('/(-[-]{1,})/',$separator ,$str);
    $str = preg_replace('/(,)/',$separator ,$str);
    $str=strtolower($str);
    return $str;
  }
  protected function handlePlural($string, $condition, $singular, $plural){
    if($condition) $string = str_replace($singular,$plural,$string);
    return $string;
  }
  protected function adminOnly(){
    if(!auth()->user()->adminOnly()) throw new \Exception('Você não tem acesso a essa página');
  }
  protected function devOnly(){
    if(!auth()->user()->devOnly()) throw new \Exception('Você não tem acesso a essa página');
  }
  protected function checkStatus(){
    if(auth()->user()->type != 'dev' && auth()->user()->status != 'active')
      return view('errors.status');
    return null;
  }
  #region IMAGE FUNCTIONS
  protected function uploadImages($files,$path){
    $errors = [];
    $names = [];
    if ($files != null && count($files) > 0) {
      $count = 0;
      foreach ($files as $images) {
        $count++;
        $name_images = Carbon::now()->timestamp . '_' . $count . '.' . $images->getClientOriginalExtension();

        $finalName = asset($path.$name_images);
        try{
          if(!$images->move(public_path($path), $name_images)){
            $errors[] = "Houve um erro ao subir a {$count}º imagem";
            $finalName = null;
          }
        }catch(Exception $e){
          $errors[] = "Houve um erro ao subir a {$count}º imagem";
          $finalName = null;
        }
        $names[] = $finalName;
      }
    }
    else $errors[] = "Nenhuma imagem selecionada";
    return [
      'names' => $names,
      'errors' => $errors
    ];
  }
  protected function deleteImageFromDir($path_name){
    $result = unlink(public_path($path_name));
    if($result === false) return [
      'result'=> $result,
      'response'=> 'Houve um erro inesperado ao tentar excluir imagem da galeria!'
    ];
    return [
      'result'=> $result,
      'response' => 'Imagem excluida com sucesso!'
    ];
  }
  #endregion IMAGE FUNCTIONS
  #region API FUNCTIONS
  protected function authenticated(Request $request){
    if(!$request->hasHeader('access-token')) throw new \Exception('Essa rota é autenticada.');
    if(!$authUser = \App\Models\User::whereAccessToken(
      $request->header('access-token')
    )->first()) throw new \Exception('Token de Acesso Inválido');
    return $authUser;
  }
  /**
   * @description Helper para diminuir a escrita na decisão de tipo de retorno entre JSON e Array/Objeto.
   * @params
   *  {
   *    "json": {
   *      "type": "boolean",
   *      "description": "True se desejar que o retorno seja em json"
   *    },
   *    "data": {
   *      "type": "array/object",
   *      "description": "Dados a serem tratados"
   *    }
   *  }
   * @endparams
   */
  protected function jsonOrArray($json, $data){
    return $json ? response()->json($data) : $data;
  }
  #endregion API FUNCTIONS
  #endregion LOCAL FUNCTIONS
}