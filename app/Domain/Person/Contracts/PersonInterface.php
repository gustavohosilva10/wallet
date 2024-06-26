<?php

namespace App\Domain\Person\Contracts;

use App\Models\Person;

interface PersonInterface
{
    public function getPersonsWithContact();
    public function storePerson(array $request);
    public function getPersonWithContacts(Person $person);
    public function updatePerson(Person $person, array $request);
    public function destroyPerson(Person $person);
}
