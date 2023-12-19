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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('address_id');
            $table->json('options');
            $table->unsignedBigInteger('user_id');
            $table->integer('quantity');
            $table->enum('status', [0, 1, 2, 3, 4])->default(0);
            $table->string('orderID');
            $table->text('note')->nullable();
            $table->integer('unit_price');
            $table->json('discount')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
