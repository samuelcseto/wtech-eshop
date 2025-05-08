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
        Schema::table('shipping_providers', function (Blueprint $table) {
            // Drop the unique constraint on country_id to allow multiple shipping providers per country
            $table->dropForeign(['country_id']);
            $table->dropUnique(['country_id']);
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            
            // Add a price field to store the shipping cost
            $table->decimal('price', 8, 2)->after('cost_calculation_method')->default(2.99);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shipping_providers', function (Blueprint $table) {
            // Add back the unique constraint
            $table->dropForeign(['country_id']);
            $table->unique(['country_id']);
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            
            // Drop the price field
            $table->dropColumn('price');
        });
    }
};
