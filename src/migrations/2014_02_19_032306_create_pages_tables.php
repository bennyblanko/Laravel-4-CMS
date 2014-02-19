<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePagesTables extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        // Creates the roles table
        Schema::create('pages', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('taxonomy')->default('page');
            $table->string('status')->default('draft'); //page visibility status
            $table->string('controller')->nullable();
            $table->string('action')->nullable();
            $table->string('view')->nullable();
            $table->string('admin_view')->nullable();
            $table->boolean('is_editable')->default(true);
            $table->boolean('is_deletable')->default(true);
            $table->boolean('is_https_only')->default(false);
            $table->unsignedInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('pages')->onDelete('set null');;
            $table->integer('sorting')->default(0);
            $table->string('meta_robots')->nullable()->default('INDEX,FOLLOW');
            $table->timestamps();
        });

        Schema::create('page_texts', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('language_id');
            $table->foreign('language_id')->references('id')->on('languages')->onDelete('cascade');
            $table->unsignedInteger('page_id');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->string('title')->nullable();
            $table->string('subtitle')->nullable();
            $table->string('slug')->nullable();
            $table->text('excerpt')->nullable();
            $table->mediumText('content')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->string('status')->default('draft'); // translation status
            $table->unique(array('language_id', 'slug'));
            $table->timestamps();
        });

        Schema::create('page_pages', function(Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->unsignedInteger('page_id');
            $table->foreign('page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->unsignedInteger('child_page_id');
            $table->foreign('child_page_id')->references('id')->on('pages')->onDelete('cascade');
            $table->string('taxonomy')->default('page');
            $table->integer('sorting')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('page_pages', function(Blueprint $table) {
            $table->dropForeign('page_pages_page_id_foreign');
            $table->dropForeign('page_pages_child_page_id_foreign');
        });
        Schema::table('page_texts', function(Blueprint $table) {
            $table->dropForeign('page_texts_page_id_foreign');
            $table->dropForeign('page_texts_language_id_foreign');
        });
        Schema::table('pages', function(Blueprint $table) {
            $table->dropForeign('pages_parent_id_foreign');
        });
        Schema::drop('page_pages');
        Schema::drop('page_texts');
        Schema::drop('pages');
    }

}
