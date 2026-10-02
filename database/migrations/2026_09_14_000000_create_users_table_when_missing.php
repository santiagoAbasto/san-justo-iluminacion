<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create the accounts table for clean installations that do not have the
     * legacy user schema. Existing installations keep their current table.
     */
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('email_dos')->nullable();
            $table->string('email_tres')->nullable();
            $table->string('email_cuatro')->nullable();
            $table->string('razon_social')->nullable();
            $table->string('password');
            $table->string('cuit')->nullable();
            $table->string('direccion')->nullable();
            $table->string('provincia')->nullable();
            $table->string('localidad')->nullable();
            $table->string('rol')->default('cliente');
            $table->foreignId('vendedor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('telefono')->nullable();
            $table->unsignedInteger('descuento_uno')->default(0);
            $table->unsignedInteger('descuento_dos')->default(0);
            $table->unsignedInteger('descuento_tres')->default(0);
            $table->foreignId('lista_de_precios_id')->nullable()->index();
            $table->timestamp('email_verified_at')->nullable();
            $table->boolean('autorizado')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // This migration may have adopted a pre-existing legacy table. Keep
        // accounts on rollback rather than risk deleting production user data.
    }
};
