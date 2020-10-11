<?php

namespace App\Controller\Api;

use App\Api\ApiError;
use App\Entity\Record;
use App\Form\Model\RecordModel;
use App\Form\Type\RecordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RecordController extends BaseApiController
{
    /**
     * @Route("/api/records", name="record_creation" , methods={"POST"})
     */
    public function record_creation(Request $request)
    {
        $recordModel = new RecordModel();
        $form = $this->createForm(RecordType::class, $recordModel);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $apiError = new ApiError(
                400,
                ApiError::TYPE_VALIDATION,
                $this->getErrorsFromForm($form)
            );
            $this->throwException($apiError);
        }

        $record = new Record(
            $recordModel->getTitle(),
            $recordModel->getDescription(),
            $recordModel->getArtist(),
            $recordModel->getPrice()
        );

        $this->getEntityManager()->persist($record);
        $this->getEntityManager()->flush();

        //todo return record data + url for get + change message
        return $this->response($record, 201);
    }

    /**
     * @Route("/api/records/{id}", name="record_deletion" , methods={"DELETE"})
     */
    public function record_deletion($id)
    {
        $item = $this->getDoctrine()->getRepository(Record::class)->find($id);
        if ($item) {
            $this->getEntityManager()->remove($item);
            $this->getEntityManager()->flush();
        }

        return $this->response(null, 204);
    }
}
