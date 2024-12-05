<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\Article\ArticleRequest;
use App\Models\Tenant\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $articles = Article::latest()->with('media')->paginate(10);

        return response()->json(['data' => $articles], 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(ArticleRequest $request)
    {
        $article = Article::create($request->validated());
        if ($request->hasFile('image')) {
            $article->addMedia($request->file('image'))
                ->toMediaCollection('article');
        }
        return response()->json(['message' => 'تم إضافة المقال بنجاح.'], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $article = Article::with('media')->findOrFail($id);
        return response()->json(['data' => $article], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArticleRequest $request, string $id)
    {
        $article = Article::findOrFail($id);
        $article->update($request->all());
        if ($request->hasFile('image')) {
            $article->addMedia($request->file('image'))
                ->toMediaCollection('article');
        }
        return response()->json(['message' => 'تم نحديث المقال بنجاح.'], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Article::destroy($id);
        $articles = Article::latest()->with('media')->paginate(10);

        return response()->json(['data' => $articles, 'message' => 'تم حذف المقال بنجاح.'], 200);
    }
}
