<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('cashes', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->decimal('total', 7, 2)->default(0);
      $table->decimal('goal', 7, 2)->default(0);
      $table->boolean('disabled')->default(false);
      $table->foreignId('ministry_id')->constrained('ministries');
      $table->timestamps();
    });
  }
  public function down(){
    Schema::dropIfExists('cashes');
  }
}
