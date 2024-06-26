<?php

namespace App\Domain\Contact\Contracts;

use App\Models\Contact;

interface ContactInterface
{
    public function getContacts();
    public function showContact($idContact);
    public function storeContact(array $data);
    public function updateContact(Contact $idContact, array $data);
    public function destroyContact(Contact $contact);
}
