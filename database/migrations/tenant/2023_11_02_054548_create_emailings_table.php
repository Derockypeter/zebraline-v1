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
        Schema::create('emailings', function (Blueprint $table) {
            $table->id();
            $table->string('to'); // to->text
            $table->text('toHtml'); // to->html
            $table->longText('bodyAsHtml');
            $table->string('text');
            $table->text('textAsHtml');
            $table->string('from'); // from->text
            $table->text('fromAsHtml'); // from->html
            $table->string('subject');
            $table->enum('read', ['Y', 'N'])->default('N');

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
        Schema::dropIfExists('emailings');
    }
};
