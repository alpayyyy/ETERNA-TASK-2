<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Admin Kullanıcısı Oluştur
        $admin = User::create([
            'name' => 'Admin',
            'surname' => 'Yönetici',
            'phone' => '05550000001',
            'email' => 'admin@eterna.net.tr',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Yazar Kullanıcısı Oluştur
        $yazar = User::create([
            'name' => 'Yazar',
            'surname' => 'Kalem',
            'phone' => '05550000002',
            'email' => 'yazar@eterna.net.tr',
            'password' => Hash::make('password'),
            'role' => 'yazar',
        ]);

        // 3. Normal Kullanıcı Oluştur
        $kullanici = User::create([
            'name' => 'Normal',
            'surname' => 'Okur',
            'phone' => '05550000003',
            'email' => 'okur@eterna.net.tr',
            'password' => Hash::make('password'),
            'role' => 'kullanici',
        ]);

        // 4. Kategoriler Oluştur
        $kategori1 = Category::create(['name' => 'Teknoloji', 'slug' => 'teknoloji']);
        $kategori2 = Category::create(['name' => 'Yazılım', 'slug' => 'yazilim']);
        $kategori3 = Category::create(['name' => 'Tasarım', 'slug' => 'tasarim']);

        // 5. Test Blog Yazısı Oluştur (Yazar tarafından yazılmış)
        $post = Post::create([
            'user_id' => $yazar->id,
            'title' => 'Laravel 12 ile Gelen Yenilikler',
            'slug' => Str::slug('Laravel 12 ile Gelen Yenilikler'),
            'content' => 'Laravel 12 sürümü ile birlikte Reverb gibi harika özellikler varsayılan olarak geliyor...',
            'status' => 'yayinda',
            'published_at' => now(),
        ]);

        // Yazıya kategori bağla
        $post->categories()->attach([$kategori1->id, $kategori2->id]);
    }
}