<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();

            $table->foreignId('timetable')->constrained("timetables");
            $table->foreignId('author')->constrained("users");
            $table->text("description");
            $table->timestamp("begin");
            $table->timestamp("end");
            $table->boolean("isPublic");
            $table->boolean("validated");
            $table->boolean("isExclusive");

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
        Schema::dropIfExists('events');
    }
}
