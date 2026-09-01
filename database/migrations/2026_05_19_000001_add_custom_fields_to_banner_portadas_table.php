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
        Schema::table('banner_portadas', function (Blueprint $table) {
            if (!Schema::hasColumn('banner_portadas', 'custom_title_es')) {
                $table->string('custom_title_es')->nullable()->after('text_banner_en');
            }

            if (!Schema::hasColumn('banner_portadas', 'custom_title_en')) {
                $table->string('custom_title_en')->nullable()->after('custom_title_es');
            }

            if (!Schema::hasColumn('banner_portadas', 'custom_image_es')) {
                $table->string('custom_image_es')->nullable()->after('custom_title_en');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banner_portadas', function (Blueprint $table) {
            if (Schema::hasColumn('banner_portadas', 'custom_image_es')) {
                $table->dropColumn('custom_image_es');
            }

            if (Schema::hasColumn('banner_portadas', 'custom_title_en')) {
                $table->dropColumn('custom_title_en');
            }

            if (Schema::hasColumn('banner_portadas', 'custom_title_es')) {
                $table->dropColumn('custom_title_es');
            }
        });
    }
};
