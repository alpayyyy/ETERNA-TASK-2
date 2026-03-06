<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Post;

class DeleteUncommentedPosts extends Command
{
    // Terminalden çalıştıracağımız komutun adı
    protected $signature = 'posts:cleanup-uncommented';

    // Komutun ne işe yaradığını anlatan açıklama
    protected $description = 'Bir hafta boyunca hiç yorum almayan yazıları otomatik olarak siler (Soft Delete)';

    public function handle()
    {
        // Şu anki zamandan 7 gün öncesini alıyoruz
        $oneWeekAgo = now()->subDays(7);

        // 7 günden eski olan VE hiç yorumu olmayan yazıları buluyoruz
        $postsToDelete = Post::where('created_at', '<=', $oneWeekAgo)
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