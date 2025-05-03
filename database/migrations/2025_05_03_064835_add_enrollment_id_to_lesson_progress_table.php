<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('lesson_progress', function (Blueprint $table) {
        $table->unsignedBigInteger('enrollment_id')->after('id')->nullable();

        $table->foreign('enrollment_id')->references('id')->on('enrollments')->onDelete('cascade');
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lesson_progress', function (Blueprint $table) {
            //
        });
    }
};
