<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\StatisticsRepositoryInterface;

/**
 * @OA\Tag(
 *     name="Statistiques",
 *     description="Statistiques liées aux ventes et commandes"
 * )
 */
class StatisticsController extends Controller
{
    protected $repo;

    public function __construct(StatisticsRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }


    /**
     * @OA\Get(
     *     path="/api/statistics/total-orders",
     *     summary="Nombre total de commandes",
     *     tags={"Statistiques"},
     *     description="Utilise la méthode Query Builder :
     * - `DB::table('orders')` : sélectionne la table `orders`
     * - `->count()` : compte le nombre total de lignes (commandes)",
     *     @OA\Response(
     *         response=200,
     *         description="Nombre total de commandes"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucune commande trouvée"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function totalOrders()
    {
        $total = $this->repo->totalOrders();

        if ($total === 0) {
            return response()->json([
                'message' => 'Aucune commande trouvée.'
            ], 404);
        }

        return response()->json([
            'total_orders' => $total
        ], 200);
    }


    /**
     * @OA\Get(
     *     path="/api/statistics/total-revenue",
     *     summary="Montant total des ventes",
     *     tags={"Statistiques"},
     *     description="Calcule le chiffre d'affaires total :
     * - `DB::table('order_plant')` : accède à la table pivot
     * - `->join('plants', ...)` : jointure pour accéder au prix de chaque plante
     * - `->select(DB::raw('SUM(order_plant.quantity * plants.price) as total'))` : somme des ventes
     * - `->value('total')` : retourne le total directement",
     *     @OA\Response(
     *         response=200,
     *         description="Montant total des ventes"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucune vente enregistrée"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function totalRevenue()
    {
        $total = $this->repo->totalRevenue();

        if (!$total) {
            return response()->json([
                'message' => 'Aucune vente enregistrée.'
            ], 404);
        }

        return response()->json([
            'total_revenue' => $total
        ], 200);
    }


  /**
     * @OA\Get(
     *     path="/api/statistics/top-plants",
     *     summary="Top 5 des plantes les plus commandées",
     *     tags={"Statistiques"},
     *     description="Retourne les plantes les plus populaires :
     * - `DB::table('order_plant')` : table pivot
     * - `->join('plants', ...)` : accède aux noms des plantes
     * - `->select('plants.name', DB::raw('SUM(order_plant.quantity) as total_quantity'))` : total commandé par plante
     * - `->groupBy('plants.name')` : groupement par nom
     * - `->orderByDesc('total_quantity')` : tri décroissant
     * - `->limit(5)` : limite aux 5 premières",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des 5 plantes les plus populaires"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucune plante commandée"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */

    public function topPlants()
    {
        $plants = $this->repo->topPlants();

        if ($plants->isEmpty()) {
            return response()->json([
                'message' => 'Aucune plante commandée.'
            ], 404);
        }

        return response()->json([
            'top_plants' => $plants
        ], 200);
    }


    /**
     * @OA\Get(
     *     path="/api/statistics/sales-by-category",
     *     summary="Répartition des ventes par catégorie",
     *     tags={"Statistiques"},
     *     description="Calcule les ventes par catégorie :
     * - Jointure sur `order_plant`, `plants`, `categories`
     * - `SUM(order_plant.quantity * plants.price)` : total des ventes par catégorie
     * - `groupBy('categories.name')` : groupement par catégorie",
     *     @OA\Response(
     *         response=200,
     *         description="Liste des ventes par catégorie"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Aucune vente enregistrée par catégorie"
     *     ),
     *     security={{"bearerAuth":{}}}
     * )
     */


    public function salesByCategory()
    {
        $categories = $this->repo->salesByCategory();

        if ($categories->isEmpty()) {
            return response()->json([
                'message' => 'Aucune vente enregistrée par catégorie.'
            ], 404);
        }

        return response()->json([
            'sales_by_category' => $categories
        ], 200);
    }
}