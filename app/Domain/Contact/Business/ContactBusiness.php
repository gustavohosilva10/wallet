<?php

namespace App\Domain\Contact\Business;

use App\Domain\Contact\Contracts\ContactInterface;
use Exception;
use App\Models\Contact;

class ContactBusiness
{
    protected $contactRepository;

    public function __construct(ContactInterface $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    public function getContacts()
    {
        return $this->contactRepository->getContacts();
    }

    public function showContact($idContact)
    {
        return $this->contactRepository->showContact($idContact);
    }

    public function storeContact(array $data)
    {
        return $this->contactRepository->storeContact($data);
    }

    public function updateContact(int $idContact, array $data)
    {
        $contact = Contact::findOrFail($idContact);
        return $this->contactRepository->updateContact($contact, $data);
    }

    public function destroyContact(Contact $contact)
    {
        $this->contactRepository->destroyContact($contact);
    }
}
