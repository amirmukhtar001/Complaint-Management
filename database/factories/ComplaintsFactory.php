<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ComplaintsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => $this->faker->numberBetween(3, 7),
            'title' => 'Tech Complaint',
            'description' => $this->faker->paragraph(1),
            'status' => 'Unassigned Complaints',
        ];
    }
}
