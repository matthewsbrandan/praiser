<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Exception;

class OfficeService{
  protected $spreadsheet;
  protected $worksheet;
  
  public $status;

  public function __construct($file_name){
    try{
      $this->spreadsheet = IOFactory::load($file_name);
      
      $worksheets = [];
      foreach ($this->spreadsheet->getWorksheetIterator() as $worksheet) {
          $worksheets[] = $worksheet->toArray();
      }

      if(count($worksheets) == 0 || count($worksheets[0]) == 0) $this->status = (object)[
          'result' => false,
          'response' => 'A planilha está vazia'
      ];
      else{
        $this->worksheet = $worksheets[0];
        $this->status = (object)[
          'result' => true,
          'response' => 'Planilha carregada'
        ];
      }
    }catch(Exception  $exception){
      $this->status = (object)[
        'result' => false,
        'response' => 'Houve um erro ao carregar a planilha'
      ];
    }
  }
  public function loadPraises(){
    $loaded = $this->removeRowsNull();
    $praises = [];
    foreach($loaded as $index => $row){
      if($index < 2) continue;
      if($row[0] && $row[1] && strlen($row[0]) > 0 && strlen($row[1]) > 0){
        $praises[] = [
          'name' => $row[0],
          'singer' => $row[1],
        ];
      }
    }
    return $praises;
  }
  protected function removeRowsNull(){
    $loaded = [];
    foreach($this->worksheet as $row){
      $isEmpty = true;
      foreach($row as $col){
        if($col){
          $isEmpty = false;
          continue;
        }
      }
      if(!$isEmpty) $loaded[] = $row;
    }
    return $loaded; 
  }
}