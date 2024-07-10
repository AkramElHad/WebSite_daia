<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{

    public function create()
    {
        return view('create');
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required',
        'excerpt' => 'required',
        'content' => 'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048', 
    ]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('articles', 'public');
    }

    Article::create([
        'title' => $request->title,
        'excerpt' => $request->excerpt,
        'content' => $request->content,
        'image' => $imagePath,
    ]);

    return redirect()->route('welcome'); 
}

    public function show(Article $article)
    {
        return view('show', compact('article'));
    }

    public function edit(Article $article)
    {
        return view('edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title' => 'required',
            'excerpt' => 'required',
            'content' => 'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $article->image;

        if ($request->hasFile('image')) {
            if ($article->image) {
                Storage::delete('public/' . $article->image);
            }
            $imagePath = $request->file('image')->store('articles', 'public');
        }

        $article->update([
            'title' => $request->title,
            'excerpt' => $request->excerpt,
            'content' => $request->content,
            'image' => $imagePath,
        ]);

        return redirect()->route('welcome');
    }

    public function destroy(Article $article)
    {
        if ($article->image) {
            Storage::delete('public/' . $article->image);
        }
        $article->delete();
        return redirect()->route('welcome');
    }
    
}
