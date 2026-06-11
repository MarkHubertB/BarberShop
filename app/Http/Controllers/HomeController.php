<?php

namespace App\Http\Controllers;

use App\Models\Barber;
use App\Models\Service;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('pages.home', [
            'services' => Service::active()->orderBy('name')->get(),
            'barbers' => Barber::active()->orderBy('name')->get(),
        ]);
    }
}
