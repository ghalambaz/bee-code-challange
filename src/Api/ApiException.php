<?php


namespace App\Api;


use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{
    /**
     * @var ApiError
     */
    private $apiError;

    public function __construct(
        ApiError $apiError,
        string $message = null,
        \Throwable $previous = null,
        array $headers = [],
        ?int $code = 0
    ) {
        $this->apiError = $apiError;
        parent::__construct($apiError->getStatusCode(), $message, $previous, $headers, $code);
    }

    /**
     * @return ApiError
     */
    public function getApiError(): ApiError
    {
        return $this->apiError;
    }

}
