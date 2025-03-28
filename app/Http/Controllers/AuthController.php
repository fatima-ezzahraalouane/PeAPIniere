<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\Interfaces\AuthRepositoryInterface;
use Illuminate\Http\Request;

use App\DTO\RegisterDTO;
use App\DTO\LoginDTO;


/**
 * @OA\Info(
 *      version="1.0.0",
 *      title="PéAPInière API",
 *      description="Documentation de l'API pour la pépinière",
 * )
 *
 * @OA\SecurityScheme(
 *     type="http",
 *     description="Token JWT pour accéder aux routes protégées",
 *     name="Authorization",
 *     in="header",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     securityScheme="bearerAuth",
 * )
 */

class AuthController extends Controller
{
    protected $auth;

    public function __construct(AuthRepositoryInterface $auth)
    {
        $this->auth = $auth;
    }


    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Inscription d’un utilisateur",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="Fatima"),
     *             @OA\Property(property="email", type="string", example="fatima@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123"),
     *             @OA\Property(property="role", type="string", example="client")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur créé + token"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */

    public function register(RegisterRequest $request)
    {
        $dto = new RegisterDTO($request->validated());
        return $this->auth->register($dto);
    }


    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Connexion d’un utilisateur",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", example="fatima@example.com"),
     *             @OA\Property(property="password", type="string", example="secret123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie + token"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Identifiants incorrects"
     *     )
     * )
     */

    public function login(LoginRequest $request)
    {
        $dto = new LoginDTO($request->validated());
        return $this->auth->login($dto);
    }


    /**
     * @OA\Get(
     *     path="/api/me",
     *     summary="Afficher l’utilisateur connecté",
     *     tags={"Authentification"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Infos utilisateur connecté"
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Token manquant ou invalide"
     *     )
     * )
     */

    public function me()
    {
        return $this->auth->me();
    }
}