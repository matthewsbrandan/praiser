<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFavoritePraisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('favorite_praises', function (Blueprint $table) {
            $table->id();
            $table->text('youtube_link')->nullable();
            $table->text('cipher_link')->nullable();
            $table->string('tone')->nullable();
            $table->foreignId('praise_id')->constrained('praises');
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
        Schema::dropIfExists('favorite_praises');
    }
}
