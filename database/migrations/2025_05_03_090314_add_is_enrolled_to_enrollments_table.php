<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsEnrolledToEnrollmentsTable extends Migration
{
    public function up()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->boolean('is_enrolled')->default(true);  // Tambahkan kolom is_enrolled
        });
    }

    public function down()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('is_enrolled');  // Hapus kolom is_enrolled jika rollback
        });
    }
}
