<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show (string $slug, Category $category, Request $request)
    {
        if ($slug !== $category->slug) {
            return redirect($category->url);
        }

        if ($request->get('page') == 1) {
            return redirect($category->url);
        }

        return view('categories.show', compact('category'));
    }

    public function index () {

    }
}
