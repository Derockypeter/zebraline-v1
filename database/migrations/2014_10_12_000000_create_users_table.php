<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('names')->nullable();
            $table->string('email')->unique();
            $table->string('driverID')->nullable(); // User GIven ID from Provider
            $table->enum('driver', ['facebook', 'google'])->nullable();
            $table->enum('role', ['Client', 'Admin', 'SuperAdmin', 'Developer'])->default('Client');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();

            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
