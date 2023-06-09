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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->index('order_id');
            $table->index('product_id');
            $table->integer('count');
            $table->double('amount', 8, 2);
            $table->text('note')->nullable();
            $table->boolean('status')->default(1);
            $table->double('tip', 8, 2)->default(0);
            $table->integer('percentage')->default(0);
            $table->double('discount', 8, 2)->default(0);
            $table->timestamp('date_pay')->nullable();
            $table->timestamp('payment_removed')->nullable();
            $table->boolean('is_percentage')->default(0);
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
        Schema::dropIfExists('order_details');
    }
};
