<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use Symfony\Component\Process\Process;

class DeployController extends Controller 
{ 
  public  function deploy(Request $request) { 
    $githubPayload = $request->getContent(); 
    $githubHash = $request->header('X-Hub-Signature');
    $localToken = config('app.deploy_secret'); 
    $localHash = 'sha1=' . hash_hmac('sha1', $githubPayload, $localToken, false);
    if(hash_equals($githubHash, $localHash)) $this->deployEffect();
  }
  protected function deployEffect(){
    $root_path = base_path();
    $process = new Process([$root_path . '/deploy.sh']); 
    $process->run( function ($type, $buffer) {
      echo $buffer; 
    });
  }
}