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
        Schema::create('themes', function (Blueprint $table) {
            $table->id();
            $table->text('title');
            $table->text('imageUrl')->nullable();
            $table->enum('approved', ['F', 'T'])->default('T');
            $table->integer('toDelete')->nullable();
            $table->string('description');
            // $table->unsignedBigInteger('storetype_id')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // $table->foreign('storetype_id')->references('id')->on('store_types')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('templates');
    }
};
