<?php


namespace App\Shared\Domain\Model;


abstract class AggregateRoot
{
    private $unCommittedEvents = [];

    public function addEvent(Event $event)
    {
        $this->unCommittedEvents[] = $event;
    }

    abstract public function delete();

    public function unCommittedEvents(): array
    {
        $unCommittedEvents = $this->unCommittedEvents;
        $this->unCommittedEvents = [];

        return $unCommittedEvents;
    }

}