<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Domain\Contact\Business\ContactBusiness;
use App\Models\Contact;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_contacts()
    {
        $this->mock(ContactBusiness::class, function ($mock) {
            $mock->shouldReceive('getContacts')->once()->andReturn([]);
        });

        $response = $this->get('/api/contacts');

        $response->assertStatus(200)
            ->assertJson(['data' => []]);
    }

    public function test_can_store_contact()
    {
        $person = Person::factory()->create();

        $contactData = [
            'type' => 'phone',
            'contact' => '123456789',
            'person_id' => $person->id
        ];

        $this->mock(ContactBusiness::class, function ($mock) use ($contactData) {
            $mock->shouldReceive('storeContact')->with($contactData)->once()->andReturn(new Contact($contactData));
        });

        $response = $this->postJson('/api/contacts', $contactData);

        $response->assertStatus(201)
                 ->assertJson(['data' => $contactData]);
    }

    public function test_can_show_contact()
    {
        $contact = Contact::factory()->create();

        $this->mock(ContactBusiness::class, function ($mock) use ($contact) {
            $mock->shouldReceive('showContact')->with($contact->id)->once()->andReturn($contact);
        });

        $response = $this->get("/api/contacts/{$contact->id}");

        $response->assertStatus(200)
                 ->assertJson(['data' => $contact->toArray()]);
    }

    public function test_can_destroy_contact()
    {
        $contact = Contact::factory()->create();

        $this->mock(ContactBusiness::class, function ($mock) use ($contact) {
            $mock->shouldReceive('destroyContact')
                ->with(\Mockery::on(function ($arg) use ($contact) {
                    return $arg->id === $contact->id;
                }))
                ->once()
                ->andReturnNull();
        });

        $response = $this->delete("/api/contacts/{$contact->id}");

        $response->assertStatus(204);
    }
}
