<?php


namespace App\EventSubscriber;


use App\Api\ApiError;
use App\Api\ApiException;
use App\Api\ApiResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::EXCEPTION => [
                'processApiException'
            ],
        ];
    }

    public function processApiException(ExceptionEvent $event)
    {
        if (strpos($event->getRequest()->getPathInfo(), '/api') !== 0) {
            return;
        }

        $e = $event->getThrowable();

        $statusCode = $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 500;

        if ($statusCode >= 500) {
            return;
        }

        if ($e instanceof ApiException) {
            $apiError = $e->getApiError();
        } else {
            $apiError = new ApiError(
                $statusCode
            );

            if ($e instanceof HttpExceptionInterface) {
                $apiError->setContent('detail', $e->getMessage());
            }
        }

        $response = (new ApiResponseFactory())->createJsonExceptionResponse($apiError);

        $event->setResponse($response);
    }
}
