<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMinisterScalesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('minister_scales', function (Blueprint $table) {
            $table->id();
            $table->text('verse')->nullable();
            $table->text('about')->nullable();
            $table->enum('privacy',['public','private'])->default('private');
            $table->string('playlist')->nullable();

            $table->foreignId('scale_id')->nullable()->constrained('scales');
            $table->foreignId('user_id')->constrained('users');
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
        Schema::dropIfExists('minister_scales');
    }
}
