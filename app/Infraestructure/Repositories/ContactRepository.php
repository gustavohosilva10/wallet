<?php

namespace App\Infraestructure\Repositories;

use App\Models\Contact;
use App\Domain\Contact\Contracts\ContactInterface;
use Exception;
use DB;

class ContactRepository implements ContactInterface
{
    public function getContacts()
    {
        return Contact::all();
    }

    public function showContact($idContact)
    {
        return Contact::findOrfail($idContact);
    }

    public function storeContact(array $data)
    {
        DB::beginTransaction();

        try {
            $contact = Contact::create([
                'type' => $data['type'],
                'contact' => $data['contact'],
                'person_id' => $data['person_id']
            ]);

            if (!$contact) {
                throw new Exception('Failed to register contact.');
            }
            
            DB::commit();

            return $contact;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th);
        }
    }

    public function updateContact(Contact $contact, array $data)
    {
        DB::beginTransaction();

        try {
            $update = $contact->update([
                'type' => $data['type'],
                'contact' => $data['contact'],
                'person_id' => $data['person_id']
            ]);
           
            if (!$update) {
                throw new Exception('Failed to update contact.');
            }

            DB::commit();

            return $update;
            
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th);
        }
       
    }

    public function destroyContact(Contact $contact)
    {
        $contact->delete();
    }
}
