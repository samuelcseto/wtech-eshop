<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shipping_provider_id')->nullable();
            $table->unsignedBigInteger('shipping_address_id');

            $table->timestamp('order_date')->useCurrent();
            $table->text('payment_details')->nullable();
            $table->decimal('total_amount', 10, 2);
            $table->string('status', 20);
            $table->enum('payment_method', ['COD', 'WIRE', 'CARD']);
            $table->string('tracking_number', 100)->nullable();
            $table->decimal('shipping_cost', 10, 2)->default(0);

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');

            $table->foreign('shipping_provider_id')
                ->references('provider_id')
                ->on('shipping_providers')
                ->onDelete('set null');
                
            $table->foreign('shipping_address_id')
                ->references('address_id')
                ->on('user_addresses')
                ->onDelete('restrict');
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
