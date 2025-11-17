<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('project_id'); // Foreign key for projects table
            $table->enum('payment_type', ['initial', 'midterm', 'final'])->nullable(); // Payment type
            $table->enum('payment_method', ['cash', 'gcash', 'bank_transfer'])->default('cash'); // Default payment method
            $table->decimal('amount', 10, 2)->nullable(); // Amount with precision and scale
            $table->string('payment_image',80)->nullable(); // Path for payment image
            $table->text('remarks')->nullable(); // Optional remarks    a
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
