<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShippingProvidersTable extends Migration
{
    public function up()
    {
        Schema::create('shipping_providers', function (Blueprint $table) {
            $table->id('provider_id');
            $table->string('name', 100);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->string('tracking_url_template', 255)->nullable();
            $table->string('cost_calculation_method', 50);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shipping_providers');
    }
}
