<?php


namespace App\Shared\Domain\Model;


interface BaseRepository
{
    public function flush(): void;

    public function save(AggregateRoot $aggregateRoot);

    public function remove(AggregateRoot $aggregateRoot);
}