<?php

namespace App\Jobs;

use App\Models\Person;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessContacts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $person;
    protected $contacts;

    public function __construct(Person $person, array $contacts)
    {
        $this->person = $person;
        $this->contacts = $contacts;
    }

    public function handle()
    {
        foreach ($this->contacts as $contact) {
            $this->person->contacts()->create($contact);
        }
    }
}

