<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use App\Services\Blog;

class PublicationController extends Controller
{
    /**
     * @param Blog $blogService
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Blog $blogService)
    {
        $items = $blogService->getItems();

        return view('blog.index', compact('items'));
    }


    /**
     * Display the specified resource.
     *
     * @param \App\Models\Publication $blog
     * @param Blog $blogService
     * @return \Illuminate\Http\Response
     */
    public function show(Publication $blog, Blog $blogService): \Illuminate\Http\Response
    {
        $blogService->getDetail($blog);
        return view('blog.detail', compact('blog'));
    }

}
