<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCourseIdToModuleIdInModulesTable extends Migration
{
    public function up()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->renameColumn('course_id', 'module_id');
        });
    }

    public function down()
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->renameColumn('module_id', 'course_id');
        });
    }
}
