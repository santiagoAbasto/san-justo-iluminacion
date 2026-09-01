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
        Schema::create('trabaja_con_nosotros', function (Blueprint $table) {
            $table->id();
            $table->string('title_es')->nullable();
            $table->string('title_en')->nullable();
            $table->longText('text_es')->nullable();
            $table->longText('text_en')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trabaja_con_nosotros');
    }
};
