<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductCategoryTable extends Migration
{
    public function up()
    {
        Schema::create('product_category', function (Blueprint $table) {
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('category_id');

            $table->primary(['product_id', 'category_id']);

            $table->foreign('product_id')
                  ->references('product_id')
                  ->on('products')
                  ->onDelete('cascade');

            $table->foreign('category_id')
                  ->references('category_id')
                  ->on('categories')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('product_category');
    }
}
