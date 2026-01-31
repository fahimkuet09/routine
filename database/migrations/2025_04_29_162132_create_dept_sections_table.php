<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeptSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dept_sections', function (Blueprint $table) {
            $table->id();
            $table->integer('section_id')->nullable()->foreign('section_id')->references('id')->on('sections');
            $table->integer('department_id')->nullable()->foreign('department_id')->references('id')->on('departments');
            $table->enum('is_active', ['yes', 'no'])->default('yes');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dept_sections');
    }
}
