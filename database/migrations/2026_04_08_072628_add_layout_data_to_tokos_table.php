<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tokos', function (Blueprint $table) {
            // Kolom ini akan menyimpan semua data dekorasi (banner, urutan, dll)
            $table->json('layout_data')->nullable()->after('deskripsi');
        });
    }

    public function down()
    {
        Schema::table('tokos', function (Blueprint $table) {
            $table->dropColumn('layout_data');
        });
    }
};
