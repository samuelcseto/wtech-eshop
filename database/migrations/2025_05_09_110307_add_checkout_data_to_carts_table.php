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
        Schema::table('carts', function (Blueprint $table) {
            // Contact information
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('newsletter')->default(false)->nullable();
            
            // Shipping address
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('country')->nullable();
            
            // Shipping method
            $table->unsignedBigInteger('shipping_provider_id')->nullable();
            
            // Payment method
            $table->string('payment_method')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carts', function (Blueprint $table) {
            // Contact information
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('newsletter');
            
            // Shipping address
            $table->dropColumn('first_name');
            $table->dropColumn('last_name');
            $table->dropColumn('address_line1');
            $table->dropColumn('address_line2');
            $table->dropColumn('city');
            $table->dropColumn('postal_code');
            $table->dropColumn('country');
            
            // Shipping method
            $table->dropColumn('shipping_provider_id');
            
            // Payment method
            $table->dropColumn('payment_method');
        });
    }
};
