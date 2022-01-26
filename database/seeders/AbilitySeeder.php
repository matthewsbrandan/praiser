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
        $controller = new Controller();
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
                'slug' => $controller->generateSlug($ability)
            ]);
        }
    }
}
