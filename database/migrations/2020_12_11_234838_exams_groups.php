<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ExamsGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('exam_id');
            $table->unsignedBigInteger('group_id');
            $table->unsignedBigInteger('answer_chosen_id')->nullable();

            $table->foreign('exam_id')
                  ->references('id')->on('exams')->onDelete('cascade');
            $table->foreign('group_id')
                  ->references('id')->on('groups')->onDelete('cascade');
            $table->primary(['exam_id','group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exams_groups');
    }
}
