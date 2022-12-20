<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAvailabilitiesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('user_availabilities', function (Blueprint $table) {
      $table->id();
      $table->date('date');
      $table->date('date_final')->nullable();
      $table->string('obs')->nullable();
      $table->boolean('is_unavailable')->default(true);
      $table->foreignId('ministry_id')->constrained('ministries');
      $table->foreignId('user_id')->constrained('users');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('user_availabilities');
  }
}
