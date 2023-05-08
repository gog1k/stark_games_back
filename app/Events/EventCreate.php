<?php

namespace App\Events;

class EventCreate
{
    public int $eventId;
    public int $userId;
    public string $fields;

    /**
     * @param int $eventId
     * @param int $userId
     * @param string $fields
     */
    public function __construct(int $eventId, int $userId, string $fields)
    {
        $this->eventId = $eventId;
        $this->userId = $userId;
        $this->fields = $fields;
    }
}
