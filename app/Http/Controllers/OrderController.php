<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderStatusRequest;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\OrderRepositoryInterface;

class OrderController extends Controller
{
    protected $repo;

    public function __construct(OrderRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }


    /**
     * @OA\Post(
     *     path="/api/orders",
     *     summary="Passer une commande (client)",
     *     tags={"Commandes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="plants",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Commande enregistrée avec succès"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation"
     *     )
     * )
     */

    public function store(StoreOrderRequest $request)
    {
        return response()->json($this->repo->store($request), 201);
    }


    /**
     * @OA\Get(
     *     path="/api/my-orders",
     *     summary="Voir mes commandes (client connecté)",
     *     tags={"Commandes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des commandes du client"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucune commande trouvée"
     *     )
     * )
     */

    public function myOrders()
    {
        return response()->json($this->repo->indexForClient());
    }


    /**
     * @OA\Delete(
     *     path="/api/orders/{id}/cancel",
     *     summary="Annuler une commande (si en attente)",
     *     tags={"Commandes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la commande",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commande annulée avec succès"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Commande déjà préparée"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commande non trouvée"
     *     )
     * )
     */

    public function cancel($id)
    {
        return $this->repo->cancel($id);
    }


    /**
     * @OA\Get(
     *     path="/api/all-orders",
     *     summary="Voir toutes les commandes (employé)",
     *     tags={"Commandes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste de toutes les commandes"
     *     )
     * )
     */

    public function allOrders()
    {
        return response()->json($this->repo->indexForEmployee());
    }


    /**
     * @OA\Put(
     *     path="/api/orders/{id}/status",
     *     summary="Mettre à jour le statut d'une commande (employé)",
     *     tags={"Commandes"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID de la commande",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"status"},
     *             @OA\Property(property="status", type="string", example="livrée", enum={"en_attente", "en_preparation", "livrée"})
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Statut mis à jour"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commande non trouvée"
     *     )
     * )
     */

    public function updateStatus(UpdateOrderStatusRequest $request, $id)
    {
        return response()->json($this->repo->updateStatus($id, $request->status));
    }
}