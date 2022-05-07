<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotionPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notion_posts', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->string('title');
            $table->text('description');
            $table->text('wallpaper');
            $table->string('tags')->nullable();
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
        Schema::dropIfExists('notion_posts');
    }
}