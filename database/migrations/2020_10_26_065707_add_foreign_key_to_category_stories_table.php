<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeyToCategoryStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category_stories', function (Blueprint $table) {
            $table->foreign('category_id')->references('id')
                ->on('categories')->onDelete('cascade');
            $table->foreign('story_id')->references('id')
                ->on('stories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category_stories', function (Blueprint $table) {
            //
        });
    }
}
