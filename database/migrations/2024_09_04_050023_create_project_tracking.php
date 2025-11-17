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
        Schema::create('project_tracking', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID for the schedule
            $table->unsignedBigInteger('project_id');

            $table->enum('phase', ['phase_one','phase_two','phase_three'])->nullable();
            $table->enum('phase_progress', ['0','10', '20', '30', '40', '50', '60', '70', '80', '90', '100'])->nullable();
            $table->string('image',100)->nullable();
            $table->text('remarks')->nullable();


            
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('project_id')->references('id')->on('projects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_tracking');
    }
};
