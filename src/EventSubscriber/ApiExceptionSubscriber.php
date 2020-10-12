<?php


namespace App\EventSubscriber;


use App\Api\ApiError;
use App\Api\ApiException;
use App\Api\ApiResponseFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\SerializerInterface;

class ApiExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

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


        if ($e instanceof ApiException) {
            $apiError = $e->getApiError();
        } else {
            $apiError = new ApiError(
                $statusCode
            );
            $apiError->setType(ApiError::TYPE_INTERNAL);
            $apiError->setContent('detail', $e->getMessage());

            if ($e instanceof HttpException && $e->getStatusCode() == 401) {
                $apiError->setType($apiError::TYPE_BAD_CREDENTIAL);
            }
        }

        $response = (new ApiResponseFactory($this->serializer))->createJsonExceptionResponse($apiError);

        $event->setResponse($response);
    }
}
