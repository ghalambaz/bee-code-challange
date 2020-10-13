<?php

namespace App\Controller\Api;

use App\Api\ApiError;
use App\Entity\Record;
use App\Form\Model\RecordModel;
use App\Form\Model\RecordSearchModel;
use App\Form\Type\RecordSearchType;
use App\Form\Type\RecordType;
use App\Repository\RecordsRepository;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class RecordController extends BaseApiController
{
    /**
     * @Route("/api/records", name="record_creation" , methods={"POST"})
     * @OA\Tag(name="Store")
     * @Security(name="Bearer")
     * @OA\RequestBody(
     *     request="Record",
     *     description="Record Data with Artist ID",
     *     required=true,
     *     @OA\JsonContent(
     *              @OA\Property(property="title", type="string",example="Smelly Cat"),
     *              @OA\Property(property="description", type="string",example="Smelly Cat 2020 from friends"),
     *              @OA\Property(property="artist", type="integer",example="123",description="id of artist"),
     *              @OA\Property(property="price", type="integer",example="1099",description="1099 is equal to 10.99euro"),
     *     )
     * )
     * @OA\Response(
     *     response=201,
     *     description="Returns the records of store",
     *     @OA\JsonContent(
     *              @OA\Property(ref=@Model(type=Record::class)),
     *     )
     * )
     * @OA\Response(
     *     response="400",
     *     description="Validation Error",
     *     @OA\JsonContent(ref="#/components/schemas/ApiError")
     * )
     * @OA\Response(
     *     response="401",
     *     description="Credential Error",
     *     @OA\JsonContent(ref="#/components/schemas/JwtError")
     * )
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
     * @OA\Tag(name="Store")
     * @Security(name="Bearer")
     * @OA\Response(
     *     response=204,
     *     description="always return success!",
     * )
     * @OA\Response(
     *     response="401",
     *     description="Credential Error",
     *     @OA\JsonContent(ref="#/components/schemas/JwtError")
     * )
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
     * @OA\Tag(name="Store")
     * @Security(name="Bearer")
     * @OA\Response(
     *     response=200,
     *     description="Returns the records of store",
     *     @OA\JsonContent(
     *              @OA\Property(property="records",ref=@Model(type=Record::class)),
     *              @OA\Property(property="count", type="integer"),
     *     )
     * ),
     * @OA\Response(
     *     response="400",
     *     description="Validation Error",
     *     @OA\JsonContent(ref="#/components/schemas/ApiError")
     * ),
     * @OA\Response(
     *     response="401",
     *     description="Credential Error",
     *     @OA\JsonContent(ref="#/components/schemas/JwtError")
     * )
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
     * @OA\Tag(name="Store")
     *
     * @OA\Parameter(
     *      name="artist",
     *      in="query",
     *      description="search for name of artists",
     *      example="Joye",
     *      @OA\Schema(type="string")
     * )
     * @OA\Parameter(
     *      name="title",
     *      in="query",
     *      description="search in titles of records",
     *      example="Days of our lives",
     *      @OA\Schema(type="string")
     *)
     * @OA\Parameter(
     *      name="description",
     *      in="query",
     *      description="search in decsriptions of records",
     *      example="Friends",
     *      @OA\Schema(type="string")
     *)
     * @OA\Response(
     *     response=200,
     *     description="Returns the records of store",
     *     @OA\JsonContent(
     *              @OA\Property(property="records",ref=@Model(type=Record::class)),
     *              @OA\Property(property="count", type="integer" , example="1"),
     *     )
     * )
     * @OA\Response(
     *     response="400",
     *     description="Validation Error",
     *     @OA\JsonContent(ref="#/components/schemas/ApiError")
     * )
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
