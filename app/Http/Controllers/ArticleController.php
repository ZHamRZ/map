<?php

namespace App\Http\Controllers;

use App\Models\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::published()->latest()->paginate(12);
        return view('articles.index', compact('articles'));
    }

    public function show(Article $article)
    {
        if (!$article->is_published) {
            abort(404);
        }

        return view('articles.show', compact('article'));
    }
}
