<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Bizim komutumuz her gün gece yarısı (veya sunucunun uygun gördüğü daily saatinde) otomatik çalışsın
Schedule::command('posts:cleanup-uncommented')->daily();
