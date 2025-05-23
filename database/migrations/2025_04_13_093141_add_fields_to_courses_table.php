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
    Schema::table('courses', function (Blueprint $table) {
        $table->string('thumbnail_url')->nullable();
        $table->float('rating')->default(0);
        $table->float('progress')->default(0);
        $table->boolean('is_enrolled')->default(false);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            //
        });
    }
};
