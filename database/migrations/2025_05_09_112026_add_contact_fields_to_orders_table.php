<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Customer contact information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            
            // Customer address fields
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country', 2)->nullable();
            
            // Status fields - if they don't already exist in a different format
            $table->string('order_status')->nullable();
            $table->string('payment_status')->nullable();
            
            // Make shipping_address_id nullable since we'll store address directly
            $table->unsignedBigInteger('shipping_address_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'email',
                'phone',
                'first_name',
                'last_name',
                'address_line1',
                'address_line2',
                'city',
                'postal_code',
                'country',
                'order_status',
                'payment_status'
            ]);
            
            // Revert shipping_address_id to be required
            $table->unsignedBigInteger('shipping_address_id')->nullable(false)->change();
        });
    }
};
