<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Services\FileService;

use function PHPUnit\Framework\isNull;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Article::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\StoreArticleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreArticleRequest $request)
    {
        $validated = $request->validated();
        $article = Article::create($validated);

        //Save cover image if sent
        $file = $request->file("cover");
        if (isset($file)) {
            $storedFile = FileService::storeFile($file);
            $article->cover()->save($storedFile);
            $article->load("cover");
        }

        return response()->json($article, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function show(Article $article)
    {
        return response()->json($article, 200);
    }

    /**
     * Update the spe;cified resource in storage.
     *
     * @param  \Illuminate\Http\UpdateArticleRequest  $request
     * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateArticleRequest $request, Article $article)
    {
        $validated = $request->validated();
        $article->update($validated);

        //Update cover image if sent
        $file = $request->file("cover");
        if (isset($file)) {
            $storedFile = FileService::storeFile($file);
            $article->cover()->delete();
            $article->cover()->save($storedFile);
            $article->load("cover");
        }

        return response()->json($article, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Article  $article
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();
        return response()->json(null, 204);
    }
}
