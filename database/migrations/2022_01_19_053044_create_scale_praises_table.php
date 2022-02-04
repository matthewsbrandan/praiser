<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScalePraisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scale_praises', function (Blueprint $table) {
            $table->id();
            $table->text('youtube_link');
            $table->text('cipher_link')->nullable();
            $table->string('tone')->nullable();
            $table->foreignId('praise_id')->constrained('praises');
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('minister_scale_id')->constrained('minister_scales');
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
        Schema::dropIfExists('scale_praises');
    }
}
