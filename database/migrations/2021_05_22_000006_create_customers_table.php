<?php

use App\Enum\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @see \App\Models\Customer
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->string('telegram_chat_id');
            $table->string('username');
            $table->string('fullname');
            $table->string('gender')->default(Gender::undefined());
            $table->string('email')->unique();
            $table->string('phone_country')->default(env('PHONE_COUNTRY', 'ID'));
            $table->string('phone')->unique();
            $table->string('whatsapp_phone_country')->default(env('PHONE_COUNTRY', 'ID'));
            $table->string('whatsapp_phone')->unique();
            $table->string('account_number')->nullable()->comment('nomor rekening bank');
            $table->string('identitycard_number')->nullable()->comment('nomor ktp');
            $table->string('identitycard_image')->nullable()->comment('foto ktp');
            $table->string('location_latitude')->nullable();
            $table->string('location_longitude')->nullable();
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
        Schema::dropIfExists('customers');
    }
};
