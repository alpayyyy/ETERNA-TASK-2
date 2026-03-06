<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Post extends Model implements HasMedia
{
    use HasFactory, SoftDeletes, InteractsWithMedia, LogsActivity;

    protected $fillable = [
        'user_id', 'title', 'slug', 'content', 'status', 'published_at'
    ];

    // Sadece değişen verileri loglama kuralı
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    // İlişkiler
    public function user() { return $this->belongsTo(User::class); }
    public function categories() { return $this->belongsToMany(Category::class); }
    public function comments() { return $this->hasMany(Comment::class); }
}