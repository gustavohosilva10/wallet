<?php

namespace App\Domain\Person\Business;

use App\Domain\Person\Contracts\PersonInterface;
use App\Models\Person;

class PersonBusiness
{
    protected $personRepository;

    public function __construct(PersonInterface $personRepository)
    {
        $this->personRepository = $personRepository;
    }

    public function getPersonsWithContact()
    {
        return $this->personRepository->getPersonsWithContact();
    }

    public function storePerson(array $request)
    {
        return $this->personRepository->storePerson($request);
    }

    public function getPersonWithContacts(Person $person)
    {
        return $this->personRepository->getPersonWithContacts($person);
    }

    public function updatePerson(Person $person, array $request)
    {
        return $this->personRepository->updatePerson($person, $request);
    }

    public function destroyPerson(Person $person)
    {
        return $this->personRepository->destroyPerson($person);
    }
}
