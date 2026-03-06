<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class FeaturedPostController extends Controller
{
    public function index()
    {
        // 1. Sadece 'yayinda' olan yazıları getir (Soft delete olanlar modelden dolayı zaten otomatik hariç tutulur)
        $posts = Post::where('status', 'yayinda')
            ->with(['user:id,name,surname', 'categories', 'media'])
            ->withCount([
                // Son 24 saatteki onaylanmış yorumlar
                'comments as comments_last_24h' => function ($query) {
                    $query->where('created_at', '>=', now()->subDay())->where('is_approved', true);
                },
                // Son 7 gündeki onaylanmış yorumlar
                'comments as comments_last_7d' => function ($query) {
                    $query->where('created_at', '>=', now()->subDays(7))->where('is_approved', true);
                },
                // Toplam onaylanmış yorumlar
                'comments as comments_total' => function ($query) {
                    $query->where('is_approved', true);
                }
            ])
            ->get()
            ->map(function ($post) {
                // 2. Yorumlara göre taban skoru hesapla
                $score = ($post->comments_last_24h * 5) + 
                         ($post->comments_last_7d * 3) + 
                         ($post->comments_total * 1);

                // 3. Yaş katsayısını hesapla ve ekle
                $daysOld = $post->published_at ? \Carbon\Carbon::parse($post->published_at)->diffInDays(now()) : 999;
                
                if ($daysOld <= 1) {
                    $score += 20;
                } elseif ($daysOld <= 3) {
                    $score += 10;
                } elseif ($daysOld <= 7) {
                    $score += 5;
                }

                // 4. Skoru response'ta görünmesi için modele ekle
                $post->score = $score;
                
                return $post;
            });

        // 5. Skora göre büyükten küçüğe sırala, ilk 5'i al ve indeksleri sıfırla
        $featuredPosts = $posts->sortByDesc('score')->take(5)->values();

        return response()->json($featuredPosts);
    }
}