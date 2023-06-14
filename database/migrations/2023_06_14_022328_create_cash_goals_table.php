<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashGoalsTable extends Migration{
  public function up(){
    Schema::create('cash_goals', function (Blueprint $table) {
      $table->id();
      $table->string('title');
      $table->text('image')->nullable();
      $table->decimal('value', 7, 2)->nullable();
      $table->decimal('value_max', 7, 2)->nullable();
      $table->json('links')->nullable(); // [{name: string, href: string}]
      $table->boolean('is_completed')->default(false);
      $table->foreignId('cash_id')->constrained('cashes');
      $table->timestamps();
    });
  }

  public function down(){
    Schema::dropIfExists('cash_goals');
  }
}
