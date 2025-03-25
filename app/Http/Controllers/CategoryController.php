<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;

use App\Repositories\Interfaces\CategoryRepositoryInterface;

class CategoryController extends Controller
{
    protected $repo;

    public function __construct(CategoryRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }


    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Lister toutes les catégories",
     *     tags={"Catégories"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des catégories"
     *     )
     * )
     */

    public function index()
    {
        return response()->json($this->repo->index());
    }


    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Créer une nouvelle catégorie",
     *     tags={"Catégories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Aromatiques")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Catégorie créée"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */

    public function store(StoreCategoryRequest $request)
    {
        return response()->json($this->repo->store($request), 201);
    }


    /**
     * @OA\Get(
     *     path="/api/categories/{slug}",
     *     summary="Afficher une catégorie par son slug",
     *     tags={"Catégories"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug de la catégorie",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie trouvée"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée"
     *     )
     * )
     */

    public function show(string $slug)
    {
        return response()->json($this->repo->show($slug));
    }


    /**
     * @OA\Put(
     *     path="/api/categories/{slug}",
     *     summary="Modifier une catégorie",
     *     tags={"Catégories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug de la catégorie",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Médicinales")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie mise à jour"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée"
     *     )
     * )
     */

    public function update(UpdateCategoryRequest $request, string $slug)
    {
        return response()->json($this->repo->update($request, $slug));
    }


    /**
     * @OA\Delete(
     *     path="/api/categories/{slug}",
     *     summary="Supprimer une catégorie",
     *     tags={"Catégories"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug de la catégorie",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie supprimée"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée"
     *     )
     * )
     */

    public function destroy(string $slug)
    {
        return $this->repo->destroy($slug);
    }
}