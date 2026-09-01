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
        Schema::create('banner_portadas', function (Blueprint $table) {
            $table->id();
            $table->string('title_banner_es')->nullable();
            $table->string('title_banner_en')->nullable();
            $table->longText('text_banner_es')->nullable();
            $table->longText('text_banner_en')->nullable();
            $table->string('image_banner')->nullable();
            $table->string('title_seccion_uno_es')->nullable();
            $table->string('title_seccion_uno_en')->nullable();
            $table->longText('text_seccion_uno_es')->nullable();
            $table->longText('text_seccion_uno_en')->nullable();
            $table->string('image_seccion_uno')->nullable();
            $table->string('title_seccion_dos_es')->nullable();
            $table->string('title_seccion_dos_en')->nullable();
            $table->longText('text_seccion_dos_es')->nullable();
            $table->longText('text_seccion_dos_en')->nullable();
            $table->string('image_seccion_dos')->nullable();
            $table->string('title_seccion_tres_es')->nullable();
            $table->string('title_seccion_tres_en')->nullable();
            $table->longText('text_seccion_tres_es')->nullable();
            $table->longText('text_seccion_tres_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banner_portadas');
    }
};
