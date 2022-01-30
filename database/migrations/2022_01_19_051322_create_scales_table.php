<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scales', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('weekday');
            $table->string('hour');
            $table->string('theme')->default('A definir');
            $table->text('obs')->nullable();
            $table->boolean('published')->default(false);
            $table->foreignId('ministry_id')->constrained('ministries');
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
        Schema::dropIfExists('scales');
    }
}
