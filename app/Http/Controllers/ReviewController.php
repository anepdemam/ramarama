<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ReviewController extends Controller
{
    public function index(): View
    {
        return view('reviews');
    }
}
