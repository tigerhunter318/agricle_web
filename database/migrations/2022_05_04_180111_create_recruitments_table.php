<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecruitmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('_recruitments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('producer_id');
            $table->foreign('producer_id')->references('id')->on('users');

            $table->string('title');
            $table->text('description')->nullable();
            $table->string('post_number');
            $table->string('prefectures');
            $table->string('city');
            $table->string('workplace');
            $table->string('reward_type');
            $table->string('reward_cost')->nullable();
            $table->date('work_date_start');
            $table->date('work_date_end')->nullable();
            $table->time('work_time_start');
            $table->time('work_time_end');
            $table->string('break_time')->nullable();
            $table->boolean('lunch_mode')->default(false);
            $table->enum('pay_mode', ['cash', 'card'])->default('cash');
            $table->string('traffic_cost')->default(0);
            $table->enum('traffic_type', ['beside', 'include'])->default('beside');
            $table->integer('worker_amount')->nullable();
            $table->string('rain_mode')->nullable();
            $table->string('clothes')->nullable();
            $table->boolean('toilet')->default(false);
            $table->boolean('park')->default(false);
            $table->boolean('insurance')->default(false);
            $table->text('notice')->nullable();
            $table->string('image');

            $table->text('postscript')->nullable();

            $table->enum('status', ['draft', 'collecting', 'working', 'completed', 'canceled', 'deleted'])->default('draft');
            $table->text('comment')->nullable();
            $table->boolean('approved')->default(false);

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
        Schema::dropIfExists('_recruitments');
    }
}
