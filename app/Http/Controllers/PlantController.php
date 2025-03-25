<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePlantRequest;
use App\Http\Requests\UpdatePlantRequest;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\PlantRepositoryInterface;

class PlantController extends Controller
{
    protected $repo;

    public function __construct(PlantRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }


    /**
     * @OA\Get(
     *     path="/api/plants",
     *     summary="Lister toutes les plantes",
     *     tags={"Plantes"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des plantes avec images et catégorie"
     *     )
     * )
     */

    public function index()
    {
        return response()->json($this->repo->index());
    }


    /**
     * @OA\Get(
     *     path="/api/plants/{slug}",
     *     summary="Afficher une plante par son slug",
     *     tags={"Plantes"},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug de la plante",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plante trouvée"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plante non trouvée"
     *     )
     * )
     */

    public function show(string $slug)
    {
        return response()->json($this->repo->show($slug));
    }


    /**
     * @OA\Post(
     *     path="/api/plants",
     *     summary="Créer une nouvelle plante",
     *     tags={"Plantes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "price", "category_id", "images"},
     *             @OA\Property(property="name", type="string", example="Basilic aromatique"),
     *             @OA\Property(property="description", type="string", example="Une plante aromatique très utilisée."),
     *             @OA\Property(property="price", type="number", example=12.50),
     *             @OA\Property(property="category_id", type="integer", example=1),
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(type="string", example="https://example.com/basilic.jpg")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Plante créée avec succès"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation (ex. + de 4 images)"
     *     )
     * )
     */

    public function store(StorePlantRequest $request)
    {
        return response()->json($this->repo->store($request), 201);
    }


    /**
     * @OA\Put(
     *     path="/api/plants/{slug}",
     *     summary="Modifier une plante existante",
     *     tags={"Plantes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug de la plante à modifier",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             required={"name", "price", "category_id"},
     *             @OA\Property(property="name", type="string", example="Menthe fraîche"),
     *             @OA\Property(property="description", type="string", example="Parfumée et fraîche."),
     *             @OA\Property(property="price", type="number", example=10.00),
     *             @OA\Property(property="category_id", type="integer", example=2),
     *             @OA\Property(
     *                 property="images",
     *                 type="array",
     *                 @OA\Items(type="string", example="https://example.com/menthe1.jpg")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plante mise à jour"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Nombre d'images dépasse 4"
     *     )
     * )
     */

    public function update(UpdatePlantRequest $request, string $slug)
    {
        return response()->json($this->repo->update($request, $slug));
    }



    /**
     * @OA\Delete(
     *     path="/api/plants/{slug}",
     *     summary="Supprimer une plante",
     *     tags={"Plantes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="slug",
     *         in="path",
     *         required=true,
     *         description="Slug de la plante",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plante supprimée"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Plante non trouvée"
     *     )
     * )
     */

    public function destroy(string $slug)
    {
        return $this->repo->destroy($slug);
    }
}