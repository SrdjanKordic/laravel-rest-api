<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('operation');
            $table->string('description');
            $table->string('subject_type');
            $table->integer('subject_id');
            $table->string('causer_type');
            $table->integer('causer_id');
            $table->string('causer_ip')->nullable();
            $table->string('causer_agent')->nullable();
            $table->string('causer_os')->nullable();
            $table->string('causer_os_version')->nullable();
            $table->string('causer_platform')->nullable();
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
