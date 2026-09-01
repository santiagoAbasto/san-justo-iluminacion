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
        Schema::create('nosotros', function (Blueprint $table) {
            $table->id();

            $table->string('media_banner')->nullable();
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
            $table->string('image_seccion_tres')->nullable();
            $table->string('title_seccion_cuatro_es')->nullable();
            $table->string('title_seccion_cuatro_en')->nullable();
            $table->longText('text_seccion_cuatro_es')->nullable();
            $table->longText('text_seccion_cuatro_en')->nullable();
            $table->string('image_seccion_cuatro')->nullable();
            $table->string('title_cosas_uno_es')->nullable();
            $table->string('title_cosas_uno_en')->nullable();
            $table->longText('text_cosas_uno_es')->nullable();
            $table->longText('text_cosas_uno_en')->nullable();
            $table->string('image_cosas_uno')->nullable();
            $table->string('title_cosas_dos_es')->nullable();
            $table->string('title_cosas_dos_en')->nullable();
            $table->longText('text_cosas_dos_es')->nullable();
            $table->longText('text_cosas_dos_en')->nullable();
            $table->string('image_cosas_dos')->nullable();
            $table->string('title_cosas_tres_es')->nullable();
            $table->string('title_cosas_tres_en')->nullable();
            $table->longText('text_cosas_tres_es')->nullable();
            $table->longText('text_cosas_tres_en')->nullable();
            $table->string('image_cosas_tres')->nullable();
            $table->string('title_cosas_cuatro_es')->nullable();
            $table->string('title_cosas_cuatro_en')->nullable();
            $table->longText('text_cosas_cuatro_es')->nullable();
            $table->longText('text_cosas_cuatro_en')->nullable();
            $table->string('image_cosas_cuatro')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nosotros');
    }
};
