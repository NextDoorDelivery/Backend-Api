<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('PhoneNumber', 20);
            $table->integer('VerificationCode');
            // Foreign key to 'lk_verification_statuses' table.
            $table->integer('LkVerificationStatusId')->unsigned();
            $table->foreign('LkVerificationStatusId')
                  ->references('LkVerificationStatusId')
                  ->on('lk_verification_statuses');
            // Foreign key end.
            $table->string('DeviceUuid')->nullable();
            $table->string('RegistrarDevice', 50)->nullable();
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
        Schema::dropIfExists('customer_verifications');
    }
}
