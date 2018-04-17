<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomsLayoutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable("rooms_layouts")) {
            Schema::create('rooms_layouts', function (Blueprint $table) {
                $table->increments('id');
                $table->integer("room_id")->unsigned(); 
                $table->foreign("room_id")->references("id")->on("rooms")->onDelete('cascade');
                $table->integer("layout_id")->unsigned(); 
                $table->foreign("layout_id")->references("id")->on("layouts")->onDelete('cascade');
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
        Schema::dropIfExists('rooms_layouts');
    }
}
