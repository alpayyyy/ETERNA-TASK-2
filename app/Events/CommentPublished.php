<?php

namespace App\Events;

use App\Models\Comment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CommentPublished implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    // Yorumun yayınlanacağı canlı kanal (Örn: post.5)
    public function broadcastOn()
    {
        return new Channel('post.' . $this->comment->post_id);
    }
}