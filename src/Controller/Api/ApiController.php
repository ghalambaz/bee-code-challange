<?php


namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api_index" , methods={"GET"})
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
