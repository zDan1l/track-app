<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkOrder>
 */
class WorkOrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => \App\Models\User::factory(),
            'location_city' => fake()->city(),
            'location_district' => fake()->word(),
            'location_village' => fake()->word(),
            'work_date' => fake()->date(),
            'start_time' => '09:00',
            'end_time' => '17:00',
            'activity_details' => fake()->paragraph(),
            'site_pic' => fake()->name(),
            'status' => 'Daily',
            'bast_scan_path' => null,
            'notes' => null,
        ];
    }

    /**
     * Indicate that the work order is final.
     */
    public function final(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'Final',
            'end_time' => '17:00',
            'bast_scan_path' => 'bast/test.pdf',
        ]);
    }

}
