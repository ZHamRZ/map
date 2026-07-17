<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('place_images', function (Blueprint $table) {
            $table->string('type', 20)->nullable()->after('image_path');
            $table->string('mime_type', 100)->nullable()->after('type');
            $table->string('file_hash', 64)->nullable()->after('mime_type');
            $table->bigInteger('file_size')->nullable()->after('file_hash');
            $table->string('thumb_path')->nullable()->after('file_size');
        });
    }

    public function down(): void
    {
        Schema::table('place_images', function (Blueprint $table) {
            $table->dropColumn(['type', 'mime_type', 'file_hash', 'file_size', 'thumb_path']);
        });
    }
};
