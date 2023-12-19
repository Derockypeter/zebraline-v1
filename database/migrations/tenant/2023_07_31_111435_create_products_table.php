<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->string('content')->nullable();
            $table->string('brand')->nullable();
            $table->enum('new', ['0', '1'])->nullable();
            $table->string('slug')->nullable();
            $table->string('precision')->nullable();
            $table->float('amount', 10, 2);
            $table->string('discount')->nullable();
            $table->integer('stock')->default(0);
            $table->unsignedBigInteger('category_id');
            $table->json('combinations')->nullable();
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
        Schema::dropIfExists('products');
    }
};
