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
            $table->string('token')->unique();
            $table->string('password');
            $table->enum('peran', ['Admin', 'Guru', 'Orang Tua', 'Kepala Sekolah']);
            $table->string('nama');
            $table->string('image_path')->deafult('default.webp');
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
