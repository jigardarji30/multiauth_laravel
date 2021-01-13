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
            $table->unsignedInteger('role_id')->nullable();
            $table->string('name', 255);
            $table->string('email', 255)->unique()->nullable();
            $table->string('image', 255)->nullable();
            $table->string('phone_number', 50)->nullable();
            $table->string('descriptions', 255)->nullable();
            $table->text('social_media')->nullable();
            $table->string('google_id', 255)->nullable();
            $table->string('facebook_id', 255)->nullable();
            $table->boolean('email_notification')->default(1)->comment("0: Inactive, 1: Active");
            $table->boolean('phone_notification')->default(1)->comment("0: Inactive, 1: Active");
            $table->boolean('trusted_tenants')->default(0)->comment("0: Inactive, 1: Active");
            $table->boolean('status')->default(1)->comment("0: Inactive, 1: Active");
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->softDeletes();
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
