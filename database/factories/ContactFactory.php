<?php

namespace Database\Factories;

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'person_id' => \App\Models\Person::factory(),
            'type' => $this->faker->randomElement(['email', 'phone']),
            'contact' => match ($this->faker->randomElement(['email', 'phone'])) {
                'email' => $this->faker->email,
                'phone' => $this->faker->phoneNumber,
            },
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
