<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateServicesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id(); 
            $table->enum('category', ['landscaping', 'swimmingpool', 'maintenance', 'renovation','package']);
            $table->string('name',50);
            $table->string('design',100);
            $table->text('description');
            $table->enum('complexity', ['very_easy','easy', 'medium' , 'hard', 'very_hard']);
            $table->enum('status', ['available', 'archive'])->default('available');
            $table->enum('type', ['landscaping', 'swimmingpool'])->nullable()->comment('Applicable if category is renovation and maintainance');
            $table->timestamps();
        });

        DB::table('services')->insert([
            [
                'category' => 'maintenance',
                'name' => 'Landscaping Maintenance Service',
                'design' => 'designs/yeYQCJ9fwaiG24PN5MvX70GEDCCo70MmKeIdCAj9.jpg',
                'description' => 'Our Landscaping Maintenance Service provides comprehensive upkeep for your outdoor spaces, including lawn care, plant health monitoring, and seasonal cleanups. Designed to ensure your landscape remains vibrant and well-maintained throughout the year, this service caters specifically to the unique needs of landscaping, offering professional and reliable care for a pristine environment.',
                'complexity' => 'easy',
                'status' => 'available',
                'type' => 'landscaping',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'category' => 'maintenance',
                'name' => 'Swimming Pool Maintenance Service',
                'design' => 'designs/M1YmF9BwZkQrQ70Jrmd0kLz03Sn3h2PJi6F96Qc2.jpg',
                'description' => 'The Swimming Pool Maintenance Service offers meticulous care for your pool, including cleaning, chemical balancing, and equipment checks. Our service ensures that your swimming pool remains in optimal condition, providing a safe and enjoyable swimming experience. Ideal for keeping your pool sparkling clean and functioning smoothly throughout the year.',
                'complexity' => 'easy',
                'status' => 'available',
                'type' => 'swimmingpool',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);


    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('services');
    }

   
}
