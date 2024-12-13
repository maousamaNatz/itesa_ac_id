<?php

namespace App\Http\Controllers;

use App\Models\article_categories;
use App\Http\Requests\Storearticle_categoriesRequest;
use App\Http\Requests\Updatearticle_categoriesRequest;

class ArticleCategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Storearticle_categoriesRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(article_categories $article_categories)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(article_categories $article_categories)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Updatearticle_categoriesRequest $request, article_categories $article_categories)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(article_categories $article_categories)
    {
        //
    }
}
