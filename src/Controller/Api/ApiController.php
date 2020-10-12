<?php


namespace App\Controller\Api;

use Symfony\Component\Routing\Annotation\Route;

class ApiController extends BaseApiController
{
    /**
     * @Route("/api/index", name="api_index" , methods={"GET"})
     */
    public function index()
    {
        return $this->json(
            [
                'message' => 'Welcome to your new Api',
                'path' => 'src/Controller/ApiController.php',
            ]
        );
    }
}
