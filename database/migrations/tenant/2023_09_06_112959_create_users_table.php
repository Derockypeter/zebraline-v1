<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('names');
            $table->string('password');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->integer('visits')->nullable();
            $table->string('device_id')->nullable();
            $table->string('provider')->nullable();
            $table->enum('provider_id', ['FB', 'TW', 'GO'])->nullable();
            $table->timestamp('email_verified_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->text('avatar')->nullable();
            $table->enum('gender', ['M', 'F'])->nullable();
            $table->enum('role', ['Admin', 'Customer'])->default('Customer');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
