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
        Schema::create('nosotros_secciones', function (Blueprint $table) {
            $table->id();
            $table->string('order')->default('zzz');
            $table->string('name_es')->nullable();
            $table->string('name_en')->nullable();
            $table->longText('text_es')->nullable();
            $table->longText('text_en')->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nosotros_secciones');
    }
};
