<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('verifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('identity', 256)->nullable(false);
            $table->enum('type', ['email_confirmation', 'mobile_confirmation'])->nullable(false);
            $table->boolean('confirmed')->nullable(false)->default(false);
            $table->integer('code');
            $table->string('ip')->nullable();
            $table->string('agent')->nullable();
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
        Schema::dropIfExists('verifications');
    }
};
