<?php

namespace App\Events;

use App\Models\EventUser;

class EventCreate
{
    public EventUser $eventUser;

    /**
     * @param EventUser $eventUser
     */
    public function __construct(EventUser $eventUser)
    {
        $this->eventUser = $eventUser;
    }
}
