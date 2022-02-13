<?php

use App\Enum\Gender;
use App\Models\Branch;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @see \App\Models\User
 */
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
            $table->foreignIdFor(Branch::class)->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->string('telegram_chat_id')->nullable()->unique();
            $table->string('username');
            $table->string('fullname');
            $table->string('gender')->default(Gender::undefined())->comment('Enum of ' . Gender::class);
            $table->string('email')->unique();
            $table->string('phone_country')->default(env('PHONE_COUNTRY', 'ID'));
            $table->string('phone')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_active')->default(false);
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
