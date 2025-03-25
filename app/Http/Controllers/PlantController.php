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






// <?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Repositories\Interfaces\PlantRepositoryInterface;

// class PlantController extends Controller
// {
//     protected $repo;

//     public function __construct(PlantRepositoryInterface $repo)
//     {
//         $this->repo = $repo;
//     }



//     public function index()
//     {
//         return response()->json($this->repo->index());
//     }


   

//     public function show(string $slug)
//     {
//         return response()->json($this->repo->show($slug));
//     }


   

//     public function store(Request $request)
//     {
//         $request->validate([
//             'name'        => 'required|string|unique:plants,name',
//             'description' => 'nullable|string',
//             'price'       => 'required|numeric',
//             'category_id' => 'required|exists:categories,id',
//             'images'      => 'required|array|max:4',
//             'images.*'    => 'required|url'
//         ]);

//         // Vérification manuelle au cas où quelqu’un modifie la règle `max:4`
//         if (count($request->images) > 4) {
//             return response()->json([
//                 'error' => 'Vous ne pouvez ajouter que 4 images maximum.'
//             ], 422);
//         }

//         return response()->json($this->repo->store($request), 201);
//     }


   

//     public function update(Request $request, string $slug)
//     {
//         $request->validate([
//             'name'        => 'required|string|unique:plants,name,' . $slug . ',slug',
//             'description' => 'nullable|string',
//             'price'       => 'required|numeric',
//             'category_id' => 'required|exists:categories,id',
//             'images'      => 'nullable|array',
//             'images.*'    => 'nullable|url'
//         ]);

//         $plant = $this->repo->getBySlug($slug); // On récupère ici pour valider les images

//         $currentImagesCount = $plant->images()->count();
//         $newImagesCount = is_array($request->images) ? count($request->images) : 0;
//         $total = $currentImagesCount + $newImagesCount;

//         if ($total > 4) {
//             return response()->json([
//                 'error' => 'Vous ne pouvez pas dépasser 4 images par plante. (actuelles : ' . $currentImagesCount . ')'
//             ], 422);
//         }

//         return response()->json($this->repo->update($request, $slug));
//     }



  

//     public function destroy(string $slug)
//     {
//         return $this->repo->destroy($slug);
//     }
// }
