<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('operation'); // update, create, delete
            $table->string('description');
            $table->string('subject_type');
            $table->integer('subject_id');
            $table->integer('user_id');
            $table->string('user_ip')->nullable();
            $table->string('user_agent')->nullable();
            $table->string('user_os')->nullable();
            $table->string('user_os_version')->nullable();
            $table->string('user_platform')->nullable();
            $table->text('properties')->nullable();
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
        Schema::dropIfExists('user_activities');
    }
}
