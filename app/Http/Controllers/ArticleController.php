<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreArticleRequest;
use App\Http\Requests\UpdateArticleRequest;
use App\Models\Article;
use App\Models\File;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{

    public function index(): JsonResponse
    {
        return response()->json(Article::with('cover')->orderBy('created_at', 'DESC')->get(), 200);
    }

    public function store(StoreArticleRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $article = Article::create($validated);

        //Save cover image if sent
        $file = $request->file("cover");
        if (isset($file)) {
            $storedFile = File::storeFile($file);
            $article->cover()->save($storedFile);
            $article->load("cover");
        }

        return response()->json($article, 201);
    }

    public function show(Article $article): JsonResponse
    {
        $article->load('cover');
        return response()->json($article);
    }

    public function update(UpdateArticleRequest $request, Article $article): JsonResponse
    {
        $validated = $request->validated();
        $article->update($validated);

        //Update cover image if sent
        $file = $request->file("cover");
        if (isset($file)) {
            $storedFile = File::storeFile($file);
            $article->cover()->delete();
            $article->cover()->save($storedFile);
        }
        $article->load("cover");

        return response()->json($article);
    }

    public function destroy(Article $article): JsonResponse
    {
        $article->delete();
        return response()->json(null, 204);
    }
}
