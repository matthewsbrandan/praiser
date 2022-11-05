<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Http\Controllers\Controller;
use App\Models\Ability;

class AbilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(){
        $abilities = [
            'Guitarra',
            'Baixo',
            'Violão',
            'Teclado',
            'Bateria',
            'Cajon',
            'Back-vocal',
            'Ministro',
            'Mesário',
            'Datashow',
            'Dança'
        ];
        foreach($abilities as $ability){
            Ability::create([
                'name' => $ability,
                'slug' => $this->generateSlug($ability)
            ]);
        }
    }
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
}
