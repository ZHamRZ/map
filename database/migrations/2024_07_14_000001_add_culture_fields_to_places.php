<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->text('history')->nullable()->after('description');
            $table->text('cultural_significance')->nullable()->after('history');
            $table->string('video_url')->nullable()->after('cultural_significance');
            $table->string('audio_url')->nullable()->after('video_url');
            $table->json('translations')->nullable()->after('audio_url');
        });
    }

    public function down(): void
    {
        Schema::table('places', function (Blueprint $table) {
            $table->dropColumn(['history', 'cultural_significance', 'video_url', 'audio_url', 'translations']);
        });
    }
};
