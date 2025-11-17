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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->unsignedBigInteger('service_id');
            $table->json('service_ids')->nullable();  // JSON array to store multiple services
            $table->enum('project_status', ['pending', 'active','hold','cancel', 'finish'])->default('pending');
            $table->integer('lot_area');
            $table->decimal('cost', 10, 2);
            $table->decimal('total_cost', 10, 2);
            $table->decimal('total_paid', 10, 2)->nullable(); // Amount with precision and scale
            $table->enum('discount', ['0', '1','2','3','4','5','6','7','8','9','10','12','15'])->default('0');
            $table->timestamps();
            $table->text('description')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->dateTime('end_date')->nullable();

            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
