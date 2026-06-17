<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seasons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('series_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('number');
            $table->timestamps();

            $table->unique(['series_id', 'number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seasons');
    }
};
