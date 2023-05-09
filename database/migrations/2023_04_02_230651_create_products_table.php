<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
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
            $table->string('name',255);
            $table->text('description',255)->nullable();
            $table->double('price', 8, 2);
            $table->string('measure',255)->nullable();
            $table->double('quantityUnitMeasure', 8, 2)->nullable();
            $table->double('stock', 8, 2)->nullable();
            $table->boolean('status')->default(1);
            $table->enum('qr', ['yes', 'not', 'category']);
            $table->foreignId('category_id')->constrained();
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
};
