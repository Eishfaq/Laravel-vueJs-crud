<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::orderBy('id', 'DESC')->paginate(2);
        return \App\Http\Resources\Article::collection($articles);
    }

    public function store(Request $request)
    {
        $article = $request->isMethod('put') ? Article::findOrFail($request->id) : new Article;
        $article->id = $request->input('id');
        $article->title = $request->input('title');
        $article->content = $request->input('content');
        if($article->save()) {
            return new \App\Http\Resources\Article($article);
        }
    }

    public function update(Request $request)
    {
        $article = Article::findOrFail($request->id);
        $article->id = $request->input('id');
        $article->title = $request->input('title');
        $article->content = $request->input('content');
        if($article->save()) {
            return new \App\Http\Resources\Article($article);
        }
    }

    public function destroy($id)
    {
        // Get article
        $article = Article::findOrFail($id);
        if($article->delete()) {
            return new \App\Http\Resources\Article($article);
        }
    }

    public function search(Request $request)
    {
        $posts = Article::where('title', $request->keywords)->get();

        return response()->json($posts);
    }

}
