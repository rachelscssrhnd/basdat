<?php

namespace App\Http\Controllers;

use App\Repositories\DashboardRepository;

class DashboardController extends Controller
{
    protected $repo;

    public function __construct(DashboardRepository $repo)
    {
        $this->repo = $repo;
    }

    public function index()
    {
        return view('dashboard.index', [
            'bookingPerBulan'   => $this->repo->bookingPerBulan(),
            'bookingPerCabang'  => $this->repo->bookingPerCabang(),
            'revenueKuartal'    => $this->repo->revenuePerKuartal(),
            'revenueCabang'     => $this->repo->revenuePerCabang(),
            'distribusiTes'     => $this->repo->distribusiTes(),
            'trenTes'           => $this->repo->trenTesPerBulan(),
        ]);
    }
}
