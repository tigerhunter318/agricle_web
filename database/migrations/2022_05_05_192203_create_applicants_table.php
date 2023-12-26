<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('_applicants', function (Blueprint $table) {
            $table->id();

            $table->foreignId('recruitment_id');
            $table->foreign('recruitment_id')->references('id')->on('_recruitments');

            $table->foreignId('worker_id');
            $table->foreign('worker_id')->references('id')->on('users');

            $table->enum('status', ['waiting', 'approved', 'abandoned', 'rejected', 'fired', 'finished'])->default('waiting');

            $table->float('worker_review', 2, 1)->default(0)->nullable();
            $table->longText('worker_evaluation')->nullable();

            $table->float('recruitment_review', 2, 1)->default(0)->nullable();
            $table->longText('recruitment_evaluation')->nullable();

            $table->longText('apply_memo')->nullable();
            $table->longText('employ_memo')->nullable();

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
        Schema::dropIfExists('_applicants');
    }
}
