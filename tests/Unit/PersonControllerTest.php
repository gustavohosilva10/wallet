<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Person;
use App\Domain\Person\Business\PersonBusiness;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;

class PersonControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_persons_with_contacts()
    {
        $person = Person::factory()->create();
        
        $this->mock(PersonBusiness::class, function ($mock) use ($person) {
            $mock->shouldReceive('getPersonsWithContact')->andReturn([$person]);
        });

        $response = $this->get('/api/people');
        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_can_store_person_with_contacts()
    {
        $personData = [
            'name' => 'John Doe',
        ];

        $this->mock(PersonBusiness::class, function ($mock) use ($personData) {
            $mock->shouldReceive('storePerson')->andReturn(Person::factory()->create($personData));
        });

        $response = $this->postJson('/api/people', $personData);
        $response->assertStatus(Response::HTTP_CREATED);
    }

   public function test_can_update_person_with_contacts()
    {
        $person = Person::factory()->create();
        $updatedData = [
            'name' => 'Updated Name',
            'contacts' => [
                ['type' => 'email', 'contact' => 'updated.email@example.com'],
                ['type' => 'phone', 'contact' => '987654321'],
            ],
        ];

        $this->mock(PersonBusiness::class, function ($mock) use ($person, $updatedData) {
            $mock->shouldReceive('updatePerson')
                ->with(\Mockery::on(function ($arg) use ($person) {
                    return $arg->id === $person->id;
                }), $updatedData)
                ->andReturn($person->fresh());
        });

        $response = $this->putJson("/api/people/{$person->id}", $updatedData);

        $response->assertStatus(Response::HTTP_OK);
    }

    public function test_can_destroy_person()
    {
        $person = Person::factory()->create();

        $this->mock(PersonBusiness::class, function ($mock) use ($person) {
            $mock->shouldReceive('destroyPerson')
                ->with(\Mockery::on(function ($arg) use ($person) {
                    return $arg->id === $person->id;
                }))
                ->andReturnNull();
        });

        $response = $this->delete("/api/people/{$person->id}");

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

}
