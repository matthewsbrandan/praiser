<?php

namespace App\Services;

class SenderService{
  protected $secret_key;
  protected $baseURL;
  
  public function __construct(){
    $this->baseURL = config('services.sender.api_url');
    $this->secret_key = config('services.sender.secret_key');
  }
  public function send($url,$request){
    [$response,$err] = $this->post($url,json_encode($request));
    $response = json_decode($response,true);
    if($err) return [
      'result' => false,
      'response' => 'Houve um erro ao tentar enviar o seu email'
    ];
    return $response;
  }
  protected function post($url, $fields){
    $curl = curl_init();

    curl_setopt_array($curl, [
      CURLOPT_URL => $this->baseURL.$url,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => "",
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 30,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => "POST",
      CURLOPT_POSTFIELDS => $fields,
      CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "secret-key: ".$this->secret_key
      ],
    ]);

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    return [$response,$err];
  }
}