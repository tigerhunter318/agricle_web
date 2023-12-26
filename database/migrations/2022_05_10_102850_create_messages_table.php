<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('_messages', function (Blueprint $table) {
            $table->increments('id');

            $table->foreignId('sender_id');
            $table->foreign('sender_id')->references('id')->on('users');

            $table->foreignId('receiver_id');
            $table->foreign('receiver_id')->references('id')->on('users');

            $table->foreignId('owner_id');
            $table->foreign('owner_id')->references('id')->on('users');

            $table->foreignId('recruitment_id');
            $table->foreign('recruitment_id')->references('id')->on('_recruitments');

            $table->unsignedInteger('message_id');

            $table->text('message')->nullable();

            $table->boolean('read')->default(false);

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
        Schema::dropIfExists('_messages');
    }
}
