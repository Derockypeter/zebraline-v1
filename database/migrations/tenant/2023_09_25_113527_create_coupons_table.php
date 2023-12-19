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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->decimal('discount_percentage', 5, 2); // Percentage with 2 decimal places
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('cover')->nullable();
            $table->string('subtitle');
            $table->enum('defaultOffer', [0, 1])->default(0);
            $table->enum('defaultHero', [0, 1])->default(0);
            $table->integer('price')->nullable(); //Prices for the new promotional, discount is good, slashes the product price with the discount to get the promo price
            $table->softDeletes();
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
        Schema::dropIfExists('coupons');
    }
};
