<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('explanatory_text');
            $table->unsignedBigInteger('question_bank_id');
            $table->datetime('from');
            $table->datetime('until')->nullable();
            $table->unsignedInteger('time_available'); //In minutes
            $table->unsignedInteger('number_of_questions');
            $table->foreign('question_bank_id')
                  ->references('id')->on('question_banks')->onDelete('cascade');
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
        Schema::dropIfExists('exams');
    }
}
