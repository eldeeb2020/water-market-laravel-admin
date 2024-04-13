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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id');  // Foreign key for company relationship
            $table->foreign('company_id')->references('id')->on('companies');  // Define foreign key constraint
            $table->string('order_number')->unique();  // Unique order identifier
            $table->timestamp('order_date')->default(now());
            $table->decimal('total_amount', 8, 2);  // Example for storing total amount with precision
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
        Schema::dropIfExists('orders');
    }
};
