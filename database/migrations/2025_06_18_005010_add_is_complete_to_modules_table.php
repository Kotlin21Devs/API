<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->boolean('is_complete')->default(false)->after('content');
        });
    }

    public function down()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropColumn('is_complete');
        });
    }
};