<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    // Tüm yetkilerden önce çalışan metod. Kullanıcı admin ise doğrudan onay ver, değilse aşağıdaki fonksiyonlara in.
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        
        return null;
    }

    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id; // Sadece yazar kendi yazısını güncelleyebilir
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id; // Sadece yazar kendi yazısını silebilir
    }
}