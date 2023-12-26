<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            // fields for auth
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('email_code')->nullable();
            $table->string('password');
            $table->enum('role', ['producer', 'worker', 'admin']);

            // fields for profile
            $table->string('avatar')->default('default.png');
            $table->string('family_name');
            $table->string('name');
            $table->string('nickname')->nullable();
            $table->enum('gender', ['man', 'woman'])->nullable();
            $table->date('birthday')->nullable();
            $table->string('post_number');
            $table->string('prefectures');
            $table->string('city');
            $table->string('address');
            $table->string('contact_address')->nullable();
            $table->string('cell_phone')->nullable();
            $table->string('emergency_phone')->nullable();
            $table->string('emergency_relation')->nullable();
            $table->string('job')->nullable();
            $table->string('bio')->nullable();
            $table->string('appeal_point')->nullable();

            // fields for producer
            $table->enum('management_mode', ['individual', 'corporation', 'other'])->default('individual');
            $table->string('agency_name')->nullable();
            $table->string('agency_phone')->nullable();
            $table->boolean('insurance')->nullable();
            $table->string('other_insurance')->nullable();
            $table->string('product_name')->nullable();

            $table->boolean('approved')->default(true);

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
        Schema::dropIfExists('users');
    }
}
