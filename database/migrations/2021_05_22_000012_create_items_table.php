<?php

use App\Models\Denomination;
use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * @see \App\Models\Item
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
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Order::class)->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignIdFor(Denomination::class)->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->unsignedInteger('quantity_per_bundle');
            $table->unsignedInteger('bundle_quantity')->nullable();
            $table->unsignedInteger('quantity');
            $table->boolean('is_order_custom_quantity')->default(true);
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
        Schema::dropIfExists('items');
    }
};
