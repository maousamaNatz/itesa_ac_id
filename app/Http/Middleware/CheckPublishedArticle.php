<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Article;
use Illuminate\Http\Request;

class CheckPublishedArticle
{
    public function handle(Request $request, Closure $next)
    {
        $slug = $request->route('slug');

        $article = Article::where('slug', $slug)
                         ->where('status', 'published')
                         ->whereNotNull('published_at')
                         ->first();

        if (!$article) {
            return redirect()
                ->route('berita.index')
                ->with('error', 'Artikel tidak ditemukan atau tidak dapat diakses.');
        }

        return $next($request);
    }
}
