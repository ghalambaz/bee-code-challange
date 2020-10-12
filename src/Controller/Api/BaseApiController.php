<?php


namespace App\Controller\Api;


use App\Api\ApiError;
use App\Api\ApiException;
use App\Api\ApiResponseFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class BaseApiController extends AbstractController
{
    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function parseRequest(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            $apiError = new ApiError(400, ApiError::TYPE_PARSE_REQUEST_CONTENT);
            throw new ApiException($apiError);
        }

        return $data;
    }

    public function deserialize($data, $classRef)
    {
        return $this->serializer->deserialize($data, $classRef, 'json');
    }

    public function serializer($object, $context = null)
    {
        return $this->serializer->serialize($object, 'json', $context);
    }

    public function throwException(ApiError $apiError)
    {
        throw new ApiException($apiError);
    }

    public function response($content, $statusCode = 200)
    {
        return (new ApiResponseFactory($this->serializer))->createJsonResponse($content, $statusCode);
    }

    protected function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);
        if ($data === null) {
            $apiProblem = new ApiError(400, ApiError::TYPE_PARSE_REQUEST_CONTENT);

            throw new ApiException($apiProblem);
        }

        $clearMissing = $request->getMethod() != 'PATCH';
        $form->submit($data, $clearMissing);
    }

    protected function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    /**
     * @return \Doctrine\Persistence\ObjectManager
     */
    public function getEntityManager()
    {
        return $this->getDoctrine()->getManager();
    }


}
