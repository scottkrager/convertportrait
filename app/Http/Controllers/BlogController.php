<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::published()
            ->orderByDesc('published_at')
            ->paginate(12);

        return Inertia::render('Blog/Index', [
            'posts' => $posts,
        ]);
    }

    public function show(string $slug)
    {
        $post = BlogPost::published()->where('slug', $slug)->firstOrFail();

        return Inertia::render('Blog/Show', [
            'post' => $post,
        ]);
    }
}
