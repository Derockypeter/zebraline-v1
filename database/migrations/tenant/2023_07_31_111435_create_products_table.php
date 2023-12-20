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
            $table->string('slug')->nullable();
            $table->string('precision')->nullable();
            $table->float('price', 10, 2);
            $table->float('compareAtPrice', 10, 2)->nullable();
            $table->integer('stock')->default(0);
            $table->integer('taxed')->default(0);
            $table->integer('visible')->default(1);
            $table->text('tags')->nullable();
            $table->integer('lowstockthreshold')->nullable();
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
