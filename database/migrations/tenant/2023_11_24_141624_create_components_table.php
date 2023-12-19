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
        Schema::create('components', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('navbar_tempId');
            $table->unsignedBigInteger('hero_tempId');
            $table->unsignedBigInteger('category_tempId');
            $table->unsignedBigInteger('featured_tempId');
            $table->unsignedBigInteger('blog_tempId');
            $table->unsignedBigInteger('offer_tempId');
            $table->unsignedBigInteger('sellin_point_tempId');
            $table->unsignedBigInteger('review_tempId');
            $table->unsignedBigInteger('footer_tempId');
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
        Schema::dropIfExists('tenant_components');
    }
};
