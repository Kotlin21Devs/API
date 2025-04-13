<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDurationToLessonsTable extends Migration
{
    /**
     * Menambahkan kolom 'duration' ke tabel 'lessons'.
     */
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->string('duration')->nullable()->after('title');
        });
    }

    /**
     * Menghapus kolom 'duration' dari tabel 'lessons'.
     */
    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('duration');
        });
    }
}
