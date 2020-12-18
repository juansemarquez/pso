<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class QuestionsExamsStudents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('questions_exams_students', function (Blueprint $table) {
            $table->unsignedBigInteger('exams_students_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('answer_id')->nullable();

            $table->foreign('exams_students_id')
                  ->references('id')->on('exams_students')->onDelete('cascade');
            $table->foreign('question_id')
                  ->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('answer_id')
                  ->references('id')->on('answers')->onDelete('cascade');
            
            $table->primary(['exams_students_id','question_id']);
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions_exams_students');
    }
}
