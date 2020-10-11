<?php


namespace App\Api;


use Symfony\Component\HttpFoundation\JsonResponse;

class ApiResponseFactory
{
    public function createJsonExceptionResponse(ApiError $apiError)
    {
        $response = new JsonResponse(
            $apiError->getContent(),
            $apiError->getStatusCode()
        );
        $response->headers->set('Content-Type', 'application/problem+json');

        return $response;
    }

    public function createJsonResponse($content, int $statusCode = 200)
    {
        $response = new JsonResponse(
            $content,
            $statusCode
        );
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}
