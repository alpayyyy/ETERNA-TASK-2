<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    // Herkes kategorileri listeleyebilir
    public function index()
    {
        return response()->json(Category::all());
    }

    public function store(Request $request)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Bu işlem için yetkiniz yok.'], 403);
        }

        $request->validate(['name' => 'required|string|max:255']);

        $category = Category::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json(['message' => 'Kategori eklendi', 'category' => $category], 201);
    }

    public function update(Request $request, Category $category)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Bu işlem için yetkiniz yok.'], 403);
        }

        $request->validate(['name' => 'required|string|max:255']);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json(['message' => 'Kategori güncellendi', 'category' => $category]);
    }

    public function destroy(Request $request, Category $category)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Bu işlem için yetkiniz yok.'], 403);
        }

        $category->delete();
        return response()->json(['message' => 'Kategori silindi']);
    }
}