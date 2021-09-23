<?php

use App\Enum\DenominationType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @see \App\Models\Denomination
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
        Schema::create('denominations', function (Blueprint $table) {
            $table->id();

            $table->string('code')->unique();
            $table->string('name');
            $table->unsignedDecimal('value');
            $table->string('type')->comment('Enum of ' . DenominationType::class);
            $table->unsignedInteger('quantity_per_bundle');
            $table->unsignedInteger('minimum_order_bundle');
            $table->unsignedInteger('maximum_order_bundle');
            $table->unsignedInteger('minimum_order_quantity');
            $table->unsignedInteger('maximum_order_quantity');
            $table->boolean('can_order_custom_quantity')->default(true);
            $table->boolean('is_visible')->default(false);
            $table->string('image')->nullable();
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
        Schema::dropIfExists('denominations');
    }
};
