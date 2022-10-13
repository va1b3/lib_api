<?php

namespace App\Controller;

use App\Model\ErrorResponse;
use App\Service\UserService;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private readonly UserService $signUpService)
    {
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns 200: user signed up successfully",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="success",
     *                     type="bool"
     *                 ),
     *                 example={"success": true}
     *             )
     *         )
     *     }
     * )
     * @OA\Response(
     *     response=400,
     *     description="Returns 400: sign-up fields error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=409,
     *     description="Returns 409: user already exist",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=500,
     *     description="Returns 500: processing error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\RequestBody(
     *     required=true,
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={
     *                      "email": "example@email",
     *                      "password": "example_password"
     *                 }
     *             )
     *         )
     *     }
     * )
     */
    #[Route(path: '/api/v1/sign-up', methods: ['POST'])]
    public function signUp(Request $request): Response
    {
        return $this->json($this->signUpService->signUp(json_decode($request->getContent(),
            true)));
    }

    /**
     * @OA\Response(
     *     response=200,
     *     description="Returns 200: user JWT token",
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="token",
     *                     type="string"
     *                 ),
     *                 example={"token": "JWT token"}
     *             )
     *         )
     *     }
     * )
     * @OA\Response(
     *     response=400,
     *     description="Returns 400: sign-up fields error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=401,
     *     description="Returns 401: invalid credentials",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=409,
     *     description="Returns 409: user already exist",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\Response(
     *     response=500,
     *     description="Returns 500: processing error",
     *     @Model(type=ErrorResponse::class)
     * )
     * @OA\RequestBody(
     *     required=true,
     *     content={
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 required={"email", "password"},
     *                 @OA\Property(
     *                     property="email",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={
     *                      "email": "example@email",
     *                      "password": "example_password"
     *                 }
     *             )
     *         )
     *     }
     * )
     */
    #[Route(path: '/api/v1/get-token', methods: ['POST'])]
    public function getToken(Request $request): Response
    {
        return $this->signUpService->getToken(json_decode($request->getContent(),
            true));
    }
}