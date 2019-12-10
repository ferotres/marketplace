<?php


namespace App\Shared\Infrastructure\UI\Http\Exception;

interface ApiResponseException
{
    public function statusCode(): int;
}