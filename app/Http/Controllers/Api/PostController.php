<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PostController extends Controller
{
    // 1. Herkes yayında olan yazıları listeleyebilir
    public function index()
    {
        // Yazar bilgisi, kategoriler ve yüklenen görsellerle (media) birlikte getir
        $posts = Post::with(['user:id,name,surname', 'categories', 'media'])
            ->where('status', 'yayinda')
            ->latest()
            ->get();
            
        return response()->json($posts);
    }

    // 2. Yeni yazı oluşturma (Sadece Yazar ve Admin)
    public function store(Request $request)
    {
        if (!in_array($request->user()->role, ['admin', 'yazar'])) {
            return response()->json(['message' => 'Yazı oluşturma yetkiniz yok.'], 403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'status' => 'required|in:taslak,yayinda',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048' // Kapak görseli kontrolü
        ]);

        $post = Post::create([
            'user_id' => $request->user()->id,
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . uniqid(), // Aynı başlıktan çakışmayı önlemek için uniqid ekliyoruz
            'content' => $validated['content'],
            'status' => $validated['status'],
            'published_at' => $validated['status'] === 'yayinda' ? now() : null,
        ]);

        // Kategorileri yazıya bağla (Çoka çok ilişki)
        $post->categories()->attach($validated['categories']);

        // Görsel yüklendiyse Spatie MediaLibrary ile kaydet [cite: 10]
        if ($request->hasFile('image')) {
            $post->addMediaFromRequest('image')->toMediaCollection('cover');
        }

        return response()->json([
            'message' => 'Yazı başarıyla oluşturuldu.', 
            'post' => $post->load('media', 'categories')
        ], 201);
    }

    // 3. Tekil yazı görüntüleme
    public function show(Post $post)
    {
        return response()->json($post->load(['user:id,name,surname', 'categories', 'media', 'comments']));
    }

    // 4. Yazı güncelleme
    public function update(Request $request, Post $post)
    {
        // Daha önce yazdığımız Policy devrede: Yazar sadece kendi yazısını güncelleyebilir, admin hepsini.
        if ($request->user()->cannot('update', $post)) {
            return response()->json(['message' => 'Bu yazıyı düzenleme yetkiniz yok.'], 403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:taslak,yayinda',
            'categories' => 'sometimes|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if (isset($validated['title'])) {
            $post->title = $validated['title'];
            $post->slug = Str::slug($validated['title']) . '-' . uniqid();
        }
        if (isset($validated['content'])) $post->content = $validated['content'];
        if (isset($validated['status'])) {
            $post->status = $validated['status'];
            if ($validated['status'] === 'yayinda' && !$post->published_at) {
                $post->published_at = now();
            }
        }
        
        $post->save();

        if (isset($validated['categories'])) {
            $post->categories()->sync($validated['categories']); // sync() eski kategorileri silip yenilerini ekler
        }

        // Yeni görsel yüklendiyse eskisini silip yenisini ekle
        if ($request->hasFile('image')) {
            $post->clearMediaCollection('cover');
            $post->addMediaFromRequest('image')->toMediaCollection('cover');
        }

        return response()->json(['message' => 'Yazı güncellendi.', 'post' => $post->load('media', 'categories')]);
    }

    // 5. Yazı silme (Soft Delete) [cite: 32]
    public function destroy(Request $request, Post $post)
    {
        if ($request->user()->cannot('delete', $post)) {
            return response()->json(['message' => 'Bu yazıyı silme yetkiniz yok.'], 403);
        }

        $post->delete(); // Modelde SoftDeletes trait'i olduğu için veritabanından tamamen uçmaz, deleted_at sütunu dolar[cite: 32].

        return response()->json(['message' => 'Yazı başarıyla silindi.']);
    }
}