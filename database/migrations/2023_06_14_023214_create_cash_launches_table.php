<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashLaunchesTable extends Migration{
  public function up(){
    Schema::create('cash_launches', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->string('description');
      $table->enum('type', ['income', 'expense']);
      $table->decimal('value', 7, 2);
      $table->date('date');
      $table->foreignId('cash_id')->constrained('cashes');
      $table->timestamps();
    });
  }

  public function down(){
    Schema::dropIfExists('cash_launches');
  }
}