<?php

namespace Database\Factories;

use App\Models\Person;
use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;

class PersonFactory extends Factory
{
    protected $model = Person::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(),
        ];
    }

    public function configure()
    {
        return $this->afterCreating(function (Person $person) {
            Contact::factory()
                ->count(5)
                ->create(['person_id' => $person->id]);
        });
    }
}
