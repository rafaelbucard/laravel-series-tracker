<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(
        private readonly DashboardService $dashboard,
    ) {
        //
    }

    public function index(Request $request): View
    {
        $stats = $this->dashboard->statsFor($request->user());

        return view('dashboard', ['stats' => $stats]);
    }
}
