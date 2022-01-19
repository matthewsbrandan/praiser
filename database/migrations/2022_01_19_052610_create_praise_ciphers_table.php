<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePraiseCiphersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('praise_ciphers', function (Blueprint $table) {
            $table->id();
            $table->text('link');
            $table->string('original_tone')->nullable();
            $table->foreignId('praise_id')->constrained('praises');
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
        Schema::dropIfExists('praise_ciphers');
    }
}
