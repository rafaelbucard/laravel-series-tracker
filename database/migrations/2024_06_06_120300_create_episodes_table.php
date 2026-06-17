<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('episodes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('season_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('number');
            $table->boolean('watched')->default(false);
            $table->timestamps();

            $table->index(['season_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('episodes');
    }
};
