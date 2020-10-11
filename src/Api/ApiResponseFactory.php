<?php


namespace App\Api;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ApiResponseFactory
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * ApiResponseFactory constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function createJsonExceptionResponse(ApiError $apiError)
    {
        return new Response(
            $this->serializer->serialize($apiError->getContent(), 'json'),
            $apiError->getStatusCode(),
            ['Content-Type', 'application/problem+json']
        );
    }

    public function createJsonResponse($content, int $statusCode = 200)
    {
        return new Response(
            $this->serializer->serialize($content, 'json'),
            $statusCode,
            ['Content-Type', 'application/json']
        );
    }

}
