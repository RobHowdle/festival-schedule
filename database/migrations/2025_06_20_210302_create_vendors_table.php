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
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->nullable();
            $table->json('categories')->nullable();
            $table->longText('description')->nullable();
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->json('social_links')->nullable();
            $table->string('logo')->nullable();
            $table->longText('menu')->nullable();
            $table->json('tags')->nullable();
            $table->json('dietary_restrictions')->nullable();
            $table->json('restrictions')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};