<?php


namespace App\Controller\Api;


use App\Api\ApiError;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends BaseApiController
{
    /**
     * @Route("/api/login", name="token_creation" , methods={"POST"})
     */
    public function token_creation(Request $request, JWTEncoderInterface $JWTEncoder)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findOneBy(['username' => $request->getUser()]);


        if (!$user) {
            $this->throwException(new ApiError(404, ApiError::TYPE_ITEM_NOT_FOUND));
        }

        $isValid = $user->getPassword() == $request->getPassword() ? true : false;
        //$isValid = $this->get('security.password_encoder')
        //    ->isPasswordValid($user, $request->getPassword());

        if (!$isValid) {
            $this->throwException(new ApiError(401, ApiError::TYPE_BAD_CREDENTIAL));
        }

        $token = $JWTEncoder->encode(['username' => $user->getUsername()]);

        return $this->response(['token' => $token]);
    }

    /**
     * @Route("/api/access", name="token_access" , methods={"POST"})
     */
    public function token_access()
    {
        return $this->response('isLogin', 200);
    }
}
