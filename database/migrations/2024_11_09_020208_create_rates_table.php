<?php

// database/migrations/xxxx_xx_xx_create_rates_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateRatesTable extends Migration
{
    public function up()
    {
        Schema::create('rates', function (Blueprint $table) {
            $table->id();
            $table->string('service_type');  // e.g., 'landscaping', 'swimmingpool', 'renovation', etc.
            $table->string('region');        // e.g., 'northern_mindanao', 'other'
            $table->string('complexity');    // e.g., 'very_easy', 'easy', 'medium', 'hard', 'very_hard'
            $table->decimal('rate', 10, 2);  // Base rate for the service type, region, and complexity
            $table->timestamps();
        });

        // Insert all rates based on the provided pricing array
        DB::table('rates')->insert([
            // Landscaping
            ['service_type' => 'landscaping', 'region' => 'northern_mindanao', 'complexity' => 'very_easy', 'rate' => 2000],
            ['service_type' => 'landscaping', 'region' => 'northern_mindanao', 'complexity' => 'easy', 'rate' => 2100],
            ['service_type' => 'landscaping', 'region' => 'northern_mindanao', 'complexity' => 'medium', 'rate' => 2200],
            ['service_type' => 'landscaping', 'region' => 'northern_mindanao', 'complexity' => 'hard', 'rate' => 2300],
            ['service_type' => 'landscaping', 'region' => 'northern_mindanao', 'complexity' => 'very_hard', 'rate' => 2400],
            ['service_type' => 'landscaping', 'region' => 'other', 'complexity' => 'very_easy', 'rate' => 2500],
            ['service_type' => 'landscaping', 'region' => 'other', 'complexity' => 'easy', 'rate' => 2600],
            ['service_type' => 'landscaping', 'region' => 'other', 'complexity' => 'medium', 'rate' => 2700],
            ['service_type' => 'landscaping', 'region' => 'other', 'complexity' => 'hard', 'rate' => 2800],
            ['service_type' => 'landscaping', 'region' => 'other', 'complexity' => 'very_hard', 'rate' => 2900],

            // Swimming Pool
            ['service_type' => 'swimmingpool', 'region' => 'northern_mindanao', 'complexity' => 'very_easy', 'rate' => 10000],
            ['service_type' => 'swimmingpool', 'region' => 'northern_mindanao', 'complexity' => 'easy', 'rate' => 10500],
            ['service_type' => 'swimmingpool', 'region' => 'northern_mindanao', 'complexity' => 'medium', 'rate' => 11000],
            ['service_type' => 'swimmingpool', 'region' => 'northern_mindanao', 'complexity' => 'hard', 'rate' => 11500],
            ['service_type' => 'swimmingpool', 'region' => 'northern_mindanao', 'complexity' => 'very_hard', 'rate' => 12000],
            ['service_type' => 'swimmingpool', 'region' => 'other', 'complexity' => 'very_easy', 'rate' => 12500],
            ['service_type' => 'swimmingpool', 'region' => 'other', 'complexity' => 'easy', 'rate' => 13000],
            ['service_type' => 'swimmingpool', 'region' => 'other', 'complexity' => 'medium', 'rate' => 13500],
            ['service_type' => 'swimmingpool', 'region' => 'other', 'complexity' => 'hard', 'rate' => 14000],
            ['service_type' => 'swimmingpool', 'region' => 'other', 'complexity' => 'very_hard', 'rate' => 14500],

            // Renovation
            ['service_type' => 'renovation', 'region' => 'northern_mindanao', 'complexity' => 'very_easy', 'rate' => 2000],
            ['service_type' => 'renovation', 'region' => 'northern_mindanao', 'complexity' => 'easy', 'rate' => 2100],
            ['service_type' => 'renovation', 'region' => 'northern_mindanao', 'complexity' => 'medium', 'rate' => 2200],
            ['service_type' => 'renovation', 'region' => 'northern_mindanao', 'complexity' => 'hard', 'rate' => 2300],
            ['service_type' => 'renovation', 'region' => 'northern_mindanao', 'complexity' => 'very_hard', 'rate' => 2400],
            ['service_type' => 'renovation', 'region' => 'other', 'complexity' => 'very_easy', 'rate' => 2500],
            ['service_type' => 'renovation', 'region' => 'other', 'complexity' => 'easy', 'rate' => 2600],
            ['service_type' => 'renovation', 'region' => 'other', 'complexity' => 'medium', 'rate' => 2700],
            ['service_type' => 'renovation', 'region' => 'other', 'complexity' => 'hard', 'rate' => 2800],
            ['service_type' => 'renovation', 'region' => 'other', 'complexity' => 'very_hard', 'rate' => 2900],

            // Maintenance
            ['service_type' => 'maintenance', 'region' => 'northern_mindanao', 'complexity' => 'very_easy', 'rate' => 200],
            ['service_type' => 'maintenance', 'region' => 'northern_mindanao', 'complexity' => 'easy', 'rate' => 200],
            ['service_type' => 'maintenance', 'region' => 'northern_mindanao', 'complexity' => 'medium', 'rate' => 200],
            ['service_type' => 'maintenance', 'region' => 'northern_mindanao', 'complexity' => 'hard', 'rate' => 200],
            ['service_type' => 'maintenance', 'region' => 'northern_mindanao', 'complexity' => 'very_hard', 'rate' => 200],
            ['service_type' => 'maintenance', 'region' => 'other', 'complexity' => 'very_easy', 'rate' => 400],
            ['service_type' => 'maintenance', 'region' => 'other', 'complexity' => 'easy', 'rate' => 400],
            ['service_type' => 'maintenance', 'region' => 'other', 'complexity' => 'medium', 'rate' => 400],
            ['service_type' => 'maintenance', 'region' => 'other', 'complexity' => 'hard', 'rate' => 400],
            ['service_type' => 'maintenance', 'region' => 'other', 'complexity' => 'very_hard', 'rate' => 400],

            // Package
            ['service_type' => 'package', 'region' => 'northern_mindanao', 'complexity' => 'very_easy', 'rate' => 1000],
            ['service_type' => 'package', 'region' => 'northern_mindanao', 'complexity' => 'easy', 'rate' => 1100],
            ['service_type' => 'package', 'region' => 'northern_mindanao', 'complexity' => 'medium', 'rate' => 1200],
            ['service_type' => 'package', 'region' => 'northern_mindanao', 'complexity' => 'hard', 'rate' => 1300],
            ['service_type' => 'package', 'region' => 'northern_mindanao', 'complexity' => 'very_hard', 'rate' => 1400],
            ['service_type' => 'package', 'region' => 'other', 'complexity' => 'very_easy', 'rate' => 1500],
            ['service_type' => 'package', 'region' => 'other', 'complexity' => 'easy', 'rate' => 1600],
            ['service_type' => 'package', 'region' => 'other', 'complexity' => 'medium', 'rate' => 1700],
            ['service_type' => 'package', 'region' => 'other', 'complexity' => 'hard', 'rate' => 1800],
            ['service_type' => 'package', 'region' => 'other', 'complexity' => 'very_hard', 'rate' => 1900],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('rates');
    }
}
