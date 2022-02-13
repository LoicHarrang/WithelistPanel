<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExamsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            // Structure
            $table->text('structure');
            // Début et fin
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at');
            $table->dateTime('expires_at');
            // Fin d'examen?
            $table->boolean('finished')->default(false);
            $table->date('finished_at')->nullable();
            // Correction
            $table->integer('score')->nullable();
            $table->boolean('passed')->nullable();
            $table->boolean('passed_temporal')->nullable();
            $table->dateTime('passed_at')->nullable();
            $table->integer('passed_at_user_id')->nullable();
            $table->text('passed_message')->nullable();
            // Vérification Opérateur
            $table->boolean('review_required')->default(false);
            $table->integer('review_user_id')->nullable();
            $table->dateTime('review_at')->nullable();
            // Entretien
            $table->dateTime('interview_at')->nullable();
            $table->dateTime('interview_end_at')->nullable();
            $table->string('interview_code')->nullable();
            $table->dateTime('interview_code_at')->nullable();
            $table->boolean('interview_passed')->nullable();
            $table->boolean('withelist')->nullable();
            $table->integer('interview_user_id')->nullable();
            $table->string('interview_audio_url')->nullable();
            $table->string('interview_audio_encoded_at')->nullable();
            $table->string('interview_audio_message')->nullable();
            // ... timestamp
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('exams');
    }
}
