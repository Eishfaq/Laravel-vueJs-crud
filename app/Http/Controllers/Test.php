<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class Test extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return response()->json($articles);
    }
}
