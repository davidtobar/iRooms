<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMeetingResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable("meeting_resources")) {
            Schema::create('meeting_resources', function (Blueprint $table) {
                $table->increments('id');
                $table->integer("meeting_id")->unsigned();
                $table->foreign("meeting_id")->references("id")->on("meetings")->onDelete('cascade');
                $table->integer("resources_id")->unsigned();  
                $table->foreign("resources_id")->references("id")->on("resources")->onDelete('cascade');
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
        Schema::dropIfExists('meeting_resources');
    }
}
