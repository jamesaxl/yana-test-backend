<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->integer('quantity');
            $table->foreignId('category_id');
            $table->foreignId('provider_id');
            $table->foreignId('add_by_id');
            $table->string('logo_path')->nullable();

            $table->foreign('category_id')->references('id')
                ->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('provider_id')->references('id')
                ->on('providers')
                ->onDelete('cascade')
                ->onUpdate('cascade');;

                $table->foreign('add_by_id')->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');;

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
        Schema::dropIfExists('products');
    }
}
