<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('itinerary_packages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('icon')->nullable();
            $table->string('image_path')->nullable();
            $table->string('duration')->nullable();
            $table->json('category_list')->nullable();
            $table->json('translations')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
        });

        Schema::create('itinerary_package_place', function (Blueprint $table) {
            $table->id();
            $table->foreignId('itinerary_package_id')->constrained()->cascadeOnDelete();
            $table->foreignId('place_id')->constrained()->cascadeOnDelete();
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('itinerary_package_place');
        Schema::dropIfExists('itinerary_packages');
    }
};
