<?php

use App\Models\Ambiente;
use App\Models\Categoria;
use App\Models\Espacio;
use App\Models\ImagenProducto;
use App\Models\Linea;
use App\Models\Marca;
use App\Models\MarcaProducto;
use App\Models\Modelo;
use App\Models\SubCategoria;
use App\Models\Uso;
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
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('order')->default("zzz");
            $table->string('name')->nullable();
            $table->string('code')->nullable();
            $table->string('medidas')->nullable();
            $table->string('origen')->nullable();
            $table->string('lampara')->nullable();
            $table->string('certificado')->nullable();
            $table->string('instructivo')->nullable();
            $table->foreignIdFor(Espacio::class)->nullable()
                ->constrained('espacios')
                ->nullOnDelete();
            $table->foreignIdFor(Uso::class)->nullable()
                ->constrained('usos')
                ->nullOnDelete();
            $table->foreignIdFor(Linea::class)->nullable()
                ->constrained('lineas')
                ->nullOnDelete();
            $table->foreignIdFor(Ambiente::class)->nullable()
                ->constrained('ambientes')
                ->nullOnDelete();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('productos');
    }
};
