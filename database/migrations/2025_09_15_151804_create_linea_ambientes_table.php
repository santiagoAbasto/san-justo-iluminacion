<?php

use App\Models\Ambiente;
use App\Models\Linea;
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
        Schema::create('linea_ambientes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Linea::class)
                ->constrained('lineas')
                ->cascadeOnDelete();
            $table->foreignIdFor(Ambiente::class)
                ->constrained('ambientes')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('linea_ambientes');
    }
};
