<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // User associated with the notification
            $table->string('title'); // Title of the notification
            $table->text('message'); // Notification message
            $table->unsignedBigInteger('sent_to')->nullable(); // ID of the recipient user
            $table->boolean('is_read')->default(false); // Track if the notification has been read
            $table->timestamp('sent_at')->nullable(); // Timestamp when the notification was sent
            $table->unsignedBigInteger('type_id'); // Title of the notification


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
