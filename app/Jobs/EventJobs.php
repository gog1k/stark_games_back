<?php

declare(strict_types=1);

namespace App\Jobs;

use Exception;

class EventJobs extends Jobs
{

    /**
     * @var string
     */
    public $queue = 'events';

    /**
     * @var int
     */
    private int $eventId;

    /**
     * @var int
     */
    private int $userId;

    /**
     * @var string
     */
    private string $fields;

    /**
     * @var string
     */
    private string $eventType;

    /**
     * Create a new job instance.
     *
     * @param string $eventType
     * @param int $eventId
     * @param int $userId
     * @param string $fields
     */
    public function __construct(string $eventType, int $eventId, int $userId, string $fields)
    {
        $this->eventType = $eventType;
        $this->eventId = $eventId;
        $this->userId = $userId;
        $this->fields = $fields;
    }

    /**
     * Execute the job.
     *
     * @return bool
     * @throws Exception
     */
    public function handle(): bool
    {
        switch ($this->eventType) {
            case 'EventCreate':
//                print_r($this->event->toArray());
//                print_r($this->user->toArray());
                break;
            default:
                return false;
        }

        return true;
    }
}
