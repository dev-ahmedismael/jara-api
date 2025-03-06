<?php

namespace App\Http\Controllers\Tenant\Article;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Article\ArticleRequest;
use App\Models\Tenant\Article\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    // List all articles
    public function index(Request $request)
    {
        $articles = Article::with('media')->filter($request)->latest()->paginate($request->query('per_page', 10));
        return response()->json(['data' => $articles], 200);
    }

    // Store a new article
    public function store(ArticleRequest $request)
    {
        $article = Article::create($request->validated());

        if ($request->hasFile('image')) {
            $article->addMedia($request->file('image'))->toMediaCollection('article_images');
        }

        return response()->json(['message' => 'تم إضافة المقال بنجاح.', 'data' => $article], 201);
    }

    // Show a single article
    public function show(string $id)
    {
        $article = Article::with('media', 'comments')->findOrFail($id);
        return response()->json(['data' => $article]);
    }

    // Update an article
    public function update(ArticleRequest $request, Article $article, string $id)
    {
        $article = Article::findOrFail($id);

        $article->update($request->validated());

        if ($request->hasFile('image')) {
            $article->clearMediaCollection('article_images');
            $article->addMedia($request->file('image'))->toMediaCollection('article_images');
        }

        return response()->json(['message' => 'تم تعديل المقال بنجاح.', 'data' => $article]);
    }

    // Delete an article
    public function destroy(Article $article, string $id)
    {
        $article = Article::findOrFail($id);
        $article->clearMediaCollection('article_images');
        $article->delete();

        return response()->json(['message' => 'تم حذف المقال بنجاح.'], 200);
    }
}
