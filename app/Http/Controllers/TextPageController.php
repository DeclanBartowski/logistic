<?php

namespace App\Http\Controllers;


use App\Models\TextPage;

class TextPageController extends Controller
{
    public function __invoke(TextPage $page)
    {
        return view('text_pages.index', compact('page'));
    }
}
