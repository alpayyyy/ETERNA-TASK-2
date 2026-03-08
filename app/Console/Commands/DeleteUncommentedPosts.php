<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class DeleteUncommentedPosts extends Command
{
    protected $signature = 'posts:cleanup-uncommented';

    protected $description = 'Bir hafta boyunca hiç yorum almayan yayınlanmış yazıları otomatik olarak siler (Soft Delete)';

    public function handle()
    {
        // Şu anki zamandan 7 gün öncesini alıyoruz
        $oneWeekAgo = now()->subDays(7);

        // 7 günden eski olan ve hiç yorumu olmayan "yayınlanmış" yazıları buluyoruz
        $postsToDelete = Post::where('published_at', '<=', $oneWeekAgo)
            ->whereDoesntHave('comments')
            ->get();

        $count = $postsToDelete->count();

        // Bulunan yazıları siliyoruz (Modelde SoftDelete olduğu için çöpe gider)
        foreach ($postsToDelete as $post) {
            $post->delete();
        }

        // Terminale bilgi mesajı basıyoruz
        $this->info("{$count} adet yorum almamış eski yazı başarıyla silindi.");
    }
}