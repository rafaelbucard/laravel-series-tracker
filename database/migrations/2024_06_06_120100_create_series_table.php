<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('series', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('streaming_service_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->text('synopsis')->nullable();
            $table->unsignedSmallInteger('year')->nullable();
            $table->string('imdb_id')->nullable();
            $table->string('cover_path')->nullable();
            $table->string('cover_url')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('streaming_service_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('series');
    }
};
