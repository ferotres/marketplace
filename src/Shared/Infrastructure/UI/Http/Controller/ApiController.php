<?php


namespace App\Shared\Infrastructure\UI\Http\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class ApiController extends AbstractController
{

    public function home(string $apiVersion, string $apiAuthor): JsonResponse
    {
        return new JsonResponse(["api-version" => $apiVersion, "api-author" => $apiAuthor, "time" => new \DateTime()]);
    }

    public function response(array $payload = [], int $statusCode = 200): JsonResponse
    {
        $response = new JsonResponse($payload, $statusCode);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function exceptionResponse(?string $message = null, $statusCode = 500): JsonResponse
    {
        return $this->response(["error" => $message], $statusCode);
    }
}