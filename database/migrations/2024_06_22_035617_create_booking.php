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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id(); // Auto-incrementing ID for the booking
            $table->unsignedBigInteger('user_id');
            $table->string('name',20);
            $table->string('contact',20);
            $table->string('email',40);
            $table->string('address',20);
            $table->string('city',20);
            $table->string('province',20);
            $table->date('site_visit_date');
            $table->enum('booking_status', ['pending', 'cancelled','confirmed','visited','declined'])->default('pending');
            $table->timestamps();

            // Define foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
