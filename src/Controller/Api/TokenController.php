<?php


namespace App\Controller\Api;


use App\Api\ApiError;
use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TokenController extends BaseApiController
{
    /**
     * @Route("/api/login", name="token_creation" , methods={"POST"})
     * @OA\Tag(name="Authentication")
     *
     * @OA\Parameter(
     *      name="PHP_AUTH_USER",
     *      in="header",
     *      description="username",
     *      example="ali",
     *      required=true,
     *      @OA\Schema(type="string")
     *)
     * @OA\Parameter(
     *      name="PHP_AUTH_PW",
     *      in="header",
     *      description="password",
     *      example="password",
     *      required=true,
     *      @OA\Schema(type="string")
     *)
     *
     * @OA\Response(
     *     response=200,
     *     description="Returns token for access others",
     *     @OA\JsonContent(
     *              @OA\Property(property="token",type="string"),
     *     )
     * )
     * @OA\Response(
     *     response="404",
     *     description="Not Found Error",
     *     @OA\JsonContent(ref="#/components/schemas/ApiError")
     * )
     * @OA\Response(
     *     response="401",
     *     description="Authentication Error",
     *     @OA\JsonContent(ref="#/components/schemas/ApiError")
     * ),
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
     * @Security(name="Bearer")
     * @OA\Tag(name="Authentication")
     *
     * @OA\Response(
     *     response=200,
     *     description="You have access. Your token is valid",
     * ),
     * @OA\Response(
     *     response="401",
     *     description="Credential Error",
     *     @OA\JsonContent(ref="#/components/schemas/JwtError")
     * )
     */
    public function token_access()
    {
        return $this->response('isLogin', 200);
    }
}
