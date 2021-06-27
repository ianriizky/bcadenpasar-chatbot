<?php

use App\Models\Branch;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @see \App\Models\Order
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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Customer::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(User::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Branch::class)->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->string('code')->unique();
            $table->timestamp('schedule_date')->nullable();
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
        Schema::dropIfExists('orders');
    }
};
