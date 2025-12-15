<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepository;

class AdminAnalyticsController extends Controller
{
    protected DashboardRepository $repo;

    public function __construct(DashboardRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => [
                    'bookingPerBulan' => $this->repo->bookingPerBulan(),
                    'bookingPerCabang' => $this->repo->bookingPerCabang(),
                    'revenuePerKuartal' => $this->repo->revenuePerKuartal(),
                    'revenuePerCabang' => $this->repo->revenuePerCabang(),
                    'distribusiTes' => $this->repo->distribusiTes(),
                    'trenTesPerBulan' => $this->repo->trenTesPerBulan(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'Failed to load analytics data',
            ], 500);
        }
    }
}
