<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLkMobileDeviceTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lk_mobile_device_tokens', function (Blueprint $table) {
            $table->id();
            $table->string('PhoneNumber');
            $table->string('DeviceUuid');
            $table->string('MobileAuthToken');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lk_mobile_device_tokens');
    }
}
