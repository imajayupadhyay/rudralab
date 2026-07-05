<?php

namespace App\Http\Controllers;

use App\Support\HomePageContent;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        return Inertia::render('Home/index', [
            'content' => HomePageContent::get(),
        ]);
    }
}
