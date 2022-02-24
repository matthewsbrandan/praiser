<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->text('profile')->nullable();

            $table->string('google_id')->nullable()->unique();
            $table->string('whatsapp')->nullable();
            $table->enum('type',['user','admin','dev'])->default('user');
            $table->unsignedBigInteger('current_ministry')->nullable();
            $table->text('availability')->nullable();
            $table->text('outhers_availability')->nullable();

            $table->string('tunel')->nullable()->unique();
            
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
