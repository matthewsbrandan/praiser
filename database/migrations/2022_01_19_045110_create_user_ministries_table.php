<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMinistriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_ministries', function (Blueprint $table) {
            $table->id();
            $table->string('permission')->nullable();
            $table->string('caption')->nullable();
            $table->enum('status',['active','disabled'])->default('active');
            $table->text('obs')->nullable();
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
        Schema::dropIfExists('user_ministries');
    }
}
