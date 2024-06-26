<?php

namespace App\Infraestructure\Repositories;

use App\Models\Person;
use App\Domain\Person\Contracts\PersonInterface;
use Illuminate\Support\Facades\DB;
use Exception;

class PersonRepository implements PersonInterface
{
    public function getPersonsWithContact()
    {
        return Person::with('contacts')->get();
    }

    public function storePerson(array $personData)
    {
        DB::beginTransaction();

        try {
            $person = Person::create(['name' => $personData['name']]);

            if (!$person) {
                throw new Exception('Failed to register person.');
            }

            if (isset($personData['contacts'])) {
                foreach ($personData['contacts'] as $contact) {
                    $person->contacts()->create($contact);
                }
            }

            DB::commit();

            return $person->load('contacts');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th);
        }
        
    }

    public function getPersonWithContacts(Person $person)
    {
        return $person->load('contacts');
    }

    public function updatePerson(Person $person, array $personData)
    {
        DB::beginTransaction();

        try {
            $person->update(['name' => $personData['name']]);

            if (isset($personData['contacts'])) {
                $person->contacts()->delete();
                foreach ($personData['contacts'] as $contact) {
                    $person->contacts()->create($contact);
                }
            }

            DB::commit();

            return $person->load('contacts');
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th);
        }
    }

    public function destroyPerson(Person $person)
    {
        $person->delete();
    }
}
