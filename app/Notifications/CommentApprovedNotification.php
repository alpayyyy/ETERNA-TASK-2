<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class CommentApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    // Bildirimin gideceği kanallar
    public function via($notifiable)
    {
        return ['mail', 'database', 'broadcast'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Yazınıza Yeni Bir Yorum Geldi')
                    ->line('Yazınıza yapılan bir yorum onaylandı ve yayınlandı.')
                    ->action('Yazıyı Gör', url('/posts/' . $this->comment->post_id))
                    ->line('Teşekkürler!');
    }

    public function toArray($notifiable)
    {
        return [
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
            'message' => 'Yazınıza yeni bir yorum yapıldı.'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'comment_id' => $this->comment->id,
            'post_id' => $this->comment->post_id,
            'message' => 'Yazınıza yeni bir yorum yapıldı.'
        ]);
    }
}