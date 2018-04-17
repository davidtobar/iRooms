<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable("meetings")) {
            Schema::create('meetings', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->date('start_date');
                $table->string('start_time');
                $table->date('end_date');
                $table->string('end_time');
                $table->string('description');
                $table->string('people_notify')->nullable();
                $table->integer('status')->default('1');
                $table->integer("room")->unsigned(); 
                $table->foreign("room")->references("id")->on("rooms");
                $table->integer("layout")->unsigned(); 
                $table->foreign("layout")->references("id")->on("layouts");
                $table->integer("user_id")->unsigned(); 
                $table->foreign("user_id")->references("id")->on("users");
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meetings');
    }
}
