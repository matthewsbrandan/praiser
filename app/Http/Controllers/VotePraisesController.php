<?php

namespace App\Http\Controllers;

use App\Models\VotePraises;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VotePraisesController extends Controller{
  public function index(){
    $status = 'Em Votação'; // Levantamento | Em Votação | Em Apuração | Finalizado
    
    $praises = [];
    if($status ===  'Em Levantamento'){
      return view('vote.praises.create', [
        'praises' => $praises,
        'title' => 'Votação em Levantamento: Louvores de Novembro',
        'status' => $status
      ]);
    }
    if($status === 'Em Votação' || $status === 'Finalizado') $praises = $this->praisesInVoting();
    if($status === 'Finalizado'){
      $selecteds = ['i3E_V9Ik85I', 'Y6-zkoPqTPA'];
      $praises = array_filter($praises, function ($praise) use ($selecteds) {
        return in_array($praise->youtube_id, $selecteds);
      });
    }

    $votes = VotePraises::where('user_id', auth()->user()->id)->get()->keyBy('youtube_id');
    foreach ($praises as $praise) {
      $praise->vote = $votes[$praise->youtube_id]->type ?? null;
    }

    return view('vote.praises.index', [
      'praises' => $praises,
      'title' => (
        $status === 'Em Votação' ? 'Votação Aberta: ':
        $status === 'Em Apuração' ? 'Votação em Apuração: ':'Votação Encerrada: '
      ) . 'Louvores de Novembro',
      'status' => $status
    ]);
  }
  public function result(){
    if(auth()->user()->type !== 'dev') dd('Você não tem permissão de acessar essa tela');

    $praises = $this->praisesInVoting();

    $votes = DB::table('vote_praises')
      ->select('youtube_id')
      ->selectRaw("SUM(CASE WHEN type = 'like' THEN 1 ELSE 0 END) as likes")
      ->selectRaw("SUM(CASE WHEN type = 'dislike' THEN 1 ELSE 0 END) as deslikes")
      ->groupBy('youtube_id')
      ->get();
    
    $votesMap = $votes->keyBy('youtube_id');

    $praises = collect($praises)->map(function ($p) use ($votesMap) {
      $vote = $votesMap->get($p->youtube_id);
      $p->likes = $vote->likes ?? 0;
      $p->deslikes = $vote->deslikes ?? 0;
      $p->balance = $p->likes - $p->deslikes;
      return $p;
    });

    $orderedPraises = $praises->sortByDesc('likes')->values();
    $topLikes   = $praises->sortByDesc('likes')->take(2)->values();
    $topBalance   = $praises->sortByDesc('balance')->take(2)->values();
    $topDeslike = $praises->sortByDesc('deslikes')->take(2)->values();

    return view('vote.praises.result', [
      'orderedPraises' => $orderedPraises,
      'topLikes'       => $topLikes,
      'topBalance'     => $topBalance,
      'topDeslike'     => $topDeslike,
    ]);
  }
  public function register(Request $request){     
    $data = $request->validate([
      'youtube_id' => 'required|string|max:50',
      'type'       => 'required|in:like,dislike',
    ]);

    $userId = auth()->user()->id;
    if (!$userId) return response()->json(['error' => 'Usuário não autenticado'], 401);

    $vote = VotePraises::updateOrCreate(
      ['user_id' => $userId, 'youtube_id' => $data['youtube_id']],
      ['type' => $data['type']]
    );

    return response()->json([
      'success' => true,
      'vote'    => $vote,
    ]);
  }
  protected function formatYoutubeUrl($url) {      
    preg_match('/(?:youtu\.be\/|v=)([^&?]+)/', $url, $matches);
    return isset($matches[1]) ? "https://www.youtube.com/embed/" . $matches[1] : $url;
  }
  protected function youtubeId($url) {
    preg_match('/(?:youtu\.be\/|v=)([^&?]+)/', $url, $matches);
    return $matches[1] ?? null;
  }
  protected function praisesInVoting(){
    return [
      0 => [
        (object) ["title" => "MELHOR AMIGO / O QUE SERIA DE MIM? | NAIR NANY",                                       "youtube_id" => $this->youtubeId("https://youtu.be/lmm9iKoSpHc?si=SZu5uMOEJI7AFT85") ],
        (object) ["title" => "Fogo Em Teus Olhos | Marcos Freire",                                                   "youtube_id" => $this->youtubeId("https://youtu.be/W0R4P0FjFbE?si=6PMe6bZXigtGMccY") ],
        (object) ["title" => "A MAIOR HONRA | JULLIANY SOUZA, GUILHERME ANDRADE",                                    "youtube_id" => $this->youtubeId("https://youtu.be/V_d-GyhEyCY?si=tnxSsziP3e-gCggK") ],
        (object) ["title" => "Muralhas | Andre Valadão",                                                             "youtube_id" => $this->youtubeId("https://youtu.be/gwUYIKtejiI?si=5pndi48p1NtmBGJ7") ],
        (object) ["title" => "Grita | Canção & Louvor",                                                              "youtube_id" => $this->youtubeId("https://youtu.be/dIIj4QXCXfk?si=7R2h5yHrwujy66OL") ],
        (object) ["title" => "O Fogo Arderá + Acende Outra Vez | Attos 2 Worship",                                   "youtube_id" => $this->youtubeId("https://youtu.be/i3E_V9Ik85I?si=t9Yuo8Xv1vZuDi3r") ],
        (object) ["title" => "Romanos 8:26 | Fernanda Brum",                                                         "youtube_id" => $this->youtubeId("https://youtu.be/aaxfp4kQ0FY?si=ZSYO4TJ2Y-D2qMSq") ],
        (object) ["title" => "Tu, Porém | Marco Telles",                                                             "youtube_id" => $this->youtubeId("https://youtu.be/lUIY2ONgSgY?si=QdZ_Pv5cC2yZMYAZ") ],
        (object) ["title" => "Até Te Encontrar | Be One Music",                                                      "youtube_id" => $this->youtubeId("https://youtu.be/JwAv0LboTRo?list=RDJwAv0LboTRo")  ],
        (object) ["title" => "Ezequiel 47 | Thiago Brito",                                                           "youtube_id" => $this->youtubeId("https://youtu.be/B5-InFJCr_8?si=PWU664hHt1VY6eJe") ],
        (object) ["title" => "GRATO SOU | O Canto das Igrejas, Paulo Cesar Baruk, Netto",                            "youtube_id" => $this->youtubeId("https://youtu.be/LfSwJaT0V9A?si=877xhBOvIkmduhVm") ],
        (object) ["title" => "João 20 + Pra Sempre | Vitor Santana",                                                 "youtube_id" => $this->youtubeId("https://youtu.be/80_M97jXFpE?si=8dlfBpunfZSh9-U3") ],
        (object) ["title" => "COMO FLECHA | SAMUEL DIAS & CAROL BRAGA",                                              "youtube_id" => $this->youtubeId("https://youtu.be/lIBPtB0DS4M?si=wpv0TbKCQUjabVPq") ],
        (object) ["title" => "ELE VEM ┃ JEFFERSON E SUELLEN",                                                        "youtube_id" => $this->youtubeId("https://youtu.be/NbsfS253LCc?si=poY46Q_ZXfkk5YJK") ],
        (object) ["title" => "Dia Após Dia | Valesca Mayssa",                                                        "youtube_id" => $this->youtubeId("https://youtu.be/VQZO7cUIK2E?si=T05KbL6SjOEhzhp7") ],
        (object) ["title" => "Fé para o Impossível | Eli Soares",                                                    "youtube_id" => $this->youtubeId("https://youtu.be/8AESIsViPsg?si=eNvIowvRtBiGMOp6") ],
        (object) ["title" => "Você Nasceu Pra Dar Certo | Anderson Freire",                                          "youtube_id" => $this->youtubeId("https://youtu.be/AKhEQ7zkdLM?si=hBOj4MWJWOoc_Co8") ],
        (object) ["title" => "A Mesa | Eli Soares",                                                                  "youtube_id" => $this->youtubeId("https://youtu.be/2EGqHewoaxQ?si=W2Yai0H1s9GvqPpO") ],
        (object) ["title" => "O Sol Brilha Mais Forte Agora / Nem a Morte Nos Separou | Alessandro Vilas Boas",      "youtube_id" => $this->youtubeId("https://youtu.be/4cAeRFe7OPI")                     ],
        (object) ["title" => "Teu Povo | Ipalpha Música",                                                            "youtube_id" => $this->youtubeId("https://youtu.be/l8jzn0jA3EI?si=tdayVQ_xiiaUxJi0") ],
        (object) ["title" => "Único | Fernandinho + Gabriela Rocha",                                                 "youtube_id" => $this->youtubeId("https://youtu.be/Y6-zkoPqTPA?si=yqBS0muU7FQPxp5P") ],
        (object) ["title" => "Seu Amor | LUDI ft. Isaías Saad",                                                      "youtube_id" => $this->youtubeId("https://youtu.be/OEeY-Eu1lcU?si=G_fYdt7PjA5C6JPi") ],
        (object) ["title" => "LEÃO DE JUDÁ | JULLIANY SOUZA",                                                        "youtube_id" => $this->youtubeId("https://youtu.be/urWQSMPr1Ow?si=eNDVVUF2Is-kK012") ],
        (object) ["title" => "TU ÉS DEUS (A ELE) | O Canto das Igrejas, Paulo Cesar Baruk, Lucas & Evelyn Cortazio", "youtube_id" => $this->youtubeId("https://youtu.be/gS0Y4ID0HbY?feature=shared")      ],
        (object) ["title" => "Kailane Frauches | Eu Vou Fazer",                                                      "youtube_id" => $this->youtubeId("https://youtu.be/w2pP1DYZSTg?feature=shared")      ],
        (object) ["title" => "Som do Céu | Adoração Central",                                                        "youtube_id" => $this->youtubeId("https://youtu.be/-6h8YtD2h80?feature=shared")      ]
      ],
      1 => [
        (object) ["title" => "NOSSO CORAÇÃO QUEIMA | FHOP MUSIC", "youtube_id" => $this->youtubeId("https://youtu.be/px3ogMWZPZE") ],
        (object) ["title" => "CANÇÃO ETERNA | FHOP MUSIC",        "youtube_id" => $this->youtubeId("https://youtu.be/1IyWHmsspYk?si=hkW2OZztAlOSrS1r") ],
        (object) ["title" => "BENDITO É O REI | FHOP MUSIC",      "youtube_id" => $this->youtubeId("https://youtu.be/CmM1pcHohdI?si=dyOy0wbt3_5P1T-w") ],
        (object) ["title" => "INDESCULPÁVEL | FHOP MUSIC",        "youtube_id" => $this->youtubeId("https://youtu.be/8yN5A5V0DFc?si=z5slH6Kx1cfpXAOZ") ],
        (object) ["title" => "UM NOVO DIA | GET WORSHIP",         "youtube_id" => $this->youtubeId("https://youtu.be/6NvkZY-va0E?si=HHv2JmbixNVk60dg") ],
        (object) ["title" => "REVELA QUEM EU SOU | MATEUS BRITO", "youtube_id" => $this->youtubeId("https://youtu.be/c6FaqtKIFGQ?si=FCFjQSi7BBZ71lbg") ],
        (object) ["title" => "CANÇÃO DE MARIA | MATEUS BRITO",    "youtube_id" => $this->youtubeId("https://youtu.be/Lu7frW219f4?si=uwQTKxo8fXa10M6z") ],
        (object) ["title" => "CANÇÃO DE BARTIMEU | MATEUS BRITO", "youtube_id" => $this->youtubeId("https://youtu.be/WIzTfF1U19I?si=-XMjkcSLsLXeNm8M") ],
        (object) ["title" => "SUBLIME | FHOP MUSIC",              "youtube_id" => $this->youtubeId("https://youtu.be/7GWZwO0MdsY?si=w3fgXS-zBVF7kZAV") ],
        (object) ["title" => "O DEUS QUE EU AMO | EYSHILA",       "youtube_id" => $this->youtubeId("https://youtu.be/H0JRy0qciHE?si=68OFVHd0iMyhiSBb") ],
        (object) ["title" => "BOM TESOURO | MATEUS BRITO",        "youtube_id" => $this->youtubeId("https://youtu.be/lunIR3-DFFk?si=H2NjbpYQl7n_wp-h") ],
        (object) ["title" => "CANTE ALELUIA | RENASCER PRAISE",   "youtube_id" => $this->youtubeId("https://youtu.be/ozJIN6Pej8w?si=yyObsj42L39bHMNF") ],
      ]
    ][VotePraisesController::getIndexVotation()];
  }
  public static function getIndexVotation(){
    return 1;
  }
}