<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selling_points', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('image_or_icon')->nullable();
            $table->string('description')->nullable();
            $table->string('index')->nullable();
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
        Schema::dropIfExists('selling_points');
    }
};
