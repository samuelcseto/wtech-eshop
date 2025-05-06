<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductImagesTable extends Migration
{
    public function up()
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id('image_id');
            $table->unsignedBigInteger('product_id');

            $table->boolean('is_primary')->default(false);
            $table->integer('sort_order')->default(0);
            $table->string('image_url', 255);
            $table->string('alt_text', 255)->nullable();

            $table->timestamps();

            $table->foreign('product_id')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_images');
    }
}
