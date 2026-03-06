<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Events\CommentPublished;
use App\Notifications\CommentApprovedNotification;

class CommentController extends Controller
{
    // 1. Yorum Ekleme (Herkes yapabilir)
    public function store(Request $request, Post $post)
    {
        $request->validate([
            'content' => 'required|string|max:1000'
        ]);

        // Kural: Admin yorum yaparsa doğrudan onaylanır, diğerleri onaya düşer 
        $isApproved = $request->user()->role === 'admin' ? true : false;

        $comment = $post->comments()->create([
            'user_id' => $request->user()->id,
            'content' => $request->content,
            'is_approved' => $isApproved
        ]);

        // Eğer yorum direkt onaylandıysa (admin yaptıysa) bildirimleri ve canlı yayını tetikle
        if ($isApproved) {
            broadcast(new CommentPublished($comment));
            $post->user->notify(new CommentApprovedNotification($comment));
        }

        return response()->json([
            'message' => $isApproved ? 'Yorum eklendi.' : 'Yorumunuz eklendi, yönetici onayı bekliyor.',
            'comment' => $comment
        ], 201);
    }

    // 2. Yorumu Onaylama (Sadece Admin)
    public function approve(Request $request, Comment $comment)
    {
        // Kural: Sadece admin onaylayabilir 
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Bu işlem için yetkiniz yok.'], 403);
        }

        $comment->update(['is_approved' => true]);

        // Yorum onaylandı, bildirimleri ve canlı yayını tetikle
        broadcast(new CommentPublished($comment));
        $comment->post->user->notify(new CommentApprovedNotification($comment));

        return response()->json(['message' => 'Yorum başarıyla onaylandı.', 'comment' => $comment]);
    }

    // 3. Yorumu Silme (Sadece Admin)
    public function destroy(Request $request, Comment $comment)
    {
        // Kural: Sadece admin silebilir 
        if ($request->user()->role !== 'admin') {
            return response()->json(['message' => 'Bu işlem için yetkiniz yok.'], 403);
        }

        $comment->delete(); // Modelde SoftDeletes olduğu için çöpe gider, tamamen silinmez 

        return response()->json(['message' => 'Yorum silindi.']);
    }
}