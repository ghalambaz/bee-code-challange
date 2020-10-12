<?php

namespace App\Controller\Api;

use App\Api\ApiError;
use App\Entity\Record;
use App\Form\Model\RecordModel;
use App\Form\Model\RecordSearchModel;
use App\Form\Type\RecordSearchType;
use App\Form\Type\RecordType;
use App\Repository\RecordsRepository;
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

    /**
     * @Route("/api/records/{id}", name="record_update" , methods={"PUT","PATCH"})
     */
    public function record_update($id, Request $request)
    {
        /** @var Record $item */
        $item = $this->getEntityManager()->getRepository(Record::class)->find($id);

        if (!$item) {
            $apiError = new ApiError(
                404,
                ApiError::TYPE_ITEM_NOT_FOUND
            );
            $this->throwException($apiError);
        }

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

        if (!empty($recordModel->getTitle())) {
            $item->setTitle($recordModel->getTitle());
        }
        if (!empty($recordModel->getDescription())) {
            $item->setDescription($recordModel->getDescription());
        }
        if (!empty($recordModel->getArtist())) {
            $item->setArtist($recordModel->getArtist());
        }
        if (!empty($recordModel->getPrice())) {
            $item->setPrice($recordModel->getPrice());
        }

        $this->getEntityManager()->persist($item);
        $this->getEntityManager()->flush();

        return $this->response($item, 200);
    }

    /**
     * @Route("/api/records", name="record_list" , methods={"GET"})
     */
    public function record_list(Request $request)
    {
        $params = $request->query->all();
        $searchFilter = new RecordSearchModel();
        $form = $this->createForm(RecordSearchType::class, $searchFilter);
        $form->submit($params);

        if (!$form->isValid()) {
            $apiError = new ApiError(
                400,
                ApiError::TYPE_VALIDATION,
                $this->getErrorsFromForm($form)
            );
            $this->throwException($apiError);
        }

        $recordRepository = new RecordsRepository($this->getDoctrine());
        $result = $recordRepository->searchByParamsOrderByArtist_Title($searchFilter);


        return $this->response(
            [
                'records' => $result,
                'count' => count($result)
            ],
            200
        );
    }
}
