<template>
    <div class="min-h-screen bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div v-if="selectedPost" class="max-w-4xl mx-auto">
            <button @click="selectedPost = null" class="mb-8 inline-flex items-center text-indigo-600 font-semibold hover:text-indigo-800 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Yazı Listesine Dön
            </button>

            <article class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <img v-if="selectedPost.media && selectedPost.media.length > 0" :src="selectedPost.media[0].original_url" alt="Kapak" class="w-full h-80 object-cover">
                <div v-else class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400 font-medium">Görsel Yok</div>
                
                <div class="p-8 md:p-12">
                    <div class="flex flex-wrap gap-2 mb-6" v-if="selectedPost.categories && selectedPost.categories.length > 0">
                        <span v-for="category in selectedPost.categories" :key="category.id" class="px-3 py-1 rounded-full text-sm font-semibold bg-indigo-50 text-indigo-600">
                            {{ category.name }}
                        </span>
                    </div>
                    
                    <h1 class="text-3xl md:text-5xl font-extrabold text-gray-900 mb-6">{{ selectedPost.title }}</h1>
                    
                    <div class="prose max-w-none text-gray-700 text-lg leading-relaxed whitespace-pre-wrap">
                        {{ selectedPost.content }}
                    </div>
                </div>
            </article>

            <div class="mt-12 bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-6">Yorumlar</h3>
                
                <div v-if="!selectedPost.comments || selectedPost.comments.length === 0" class="text-gray-500 italic">
                    Bu yazıya henüz yorum yapılmamış. Postman'den bir yorum ekleyip test edebilirsin!
                </div>
                
                <div v-else class="space-y-6">
                    <div v-for="comment in selectedPost.comments" :key="comment.id" class="bg-gray-50 p-4 rounded-xl border border-gray-100">
                        <p class="text-gray-800">{{ comment.content }}</p>
                        <p class="text-xs text-gray-400 mt-2">ID: {{ comment.id }} • {{ new Date(comment.created_at).toLocaleDateString('tr-TR') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div v-else class="max-w-7xl mx-auto">
            <h1 class="text-4xl font-extrabold text-gray-900 text-center mb-12">Eterna Blog</h1>

            <div v-if="featuredPosts.length > 0" class="mb-16">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <span class="text-red-500">🔥</span> Öne Çıkan Yazılar
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div v-for="post in featuredPosts" :key="'featured-'+post.id" @click="openPost(post.id)" class="cursor-pointer bg-gradient-to-br from-indigo-50 to-blue-50 rounded-2xl shadow-md border border-indigo-100 overflow-hidden transform hover:scale-105 transition-all duration-300">
                        <img v-if="post.media && post.media.length > 0" :src="post.media[0].original_url" alt="Kapak" class="w-full h-48 object-cover">
                        <div v-else class="w-full h-48 bg-indigo-200 flex items-center justify-center text-indigo-500 font-medium">Görsel Yok</div>
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-3">
                                <span class="text-xs font-bold px-3 py-1 rounded-full bg-indigo-600 text-white shadow-sm">Skor: {{ post.score }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 line-clamp-2">{{ post.title }}</h3>
                            <p class="text-gray-600 text-sm line-clamp-3 mb-4">{{ post.content }}</p>
                            <span class="text-indigo-600 text-sm font-semibold hover:underline">Devamını Oku &rarr;</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-b border-gray-200 pb-4 mb-8">
                <h2 class="text-2xl font-bold text-gray-800">Tüm Yazılar</h2>
            </div>

            <div v-if="loading" class="flex justify-center my-12 text-blue-500">
                <svg class="animate-spin h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div v-for="post in posts" :key="post.id" @click="openPost(post.id)" class="cursor-pointer bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300">
                    <img v-if="post.media && post.media.length > 0" :src="post.media[0].original_url" alt="Kapak" class="w-full h-40 object-cover">
                    <div v-else class="w-full h-40 bg-gray-100 flex items-center justify-center text-gray-400 font-medium">Görsel Yok</div>
                    <div class="p-5">
                        <h2 class="text-lg font-bold text-gray-800 mb-2 line-clamp-1">{{ post.title }}</h2>
                        <p class="text-gray-500 text-sm line-clamp-2 mb-3">{{ post.content }}</p>
                        <span class="text-blue-500 text-sm font-semibold hover:underline">Devamını Oku &rarr;</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const posts = ref([]);
const featuredPosts = ref([]);
const loading = ref(true);
const selectedPost = ref(null); // Tıklanan yazıyı tutacağımız değişken

const fetchPosts = async () => {
    try {
        const [postsResponse, featuredResponse] = await Promise.all([
            axios.get('/api/posts'),
            axios.get('/api/featured-posts')
        ]);
        
        posts.value = postsResponse.data;
        featuredPosts.value = featuredResponse.data;
    } catch (error) {
        console.error('Veriler çekilirken hata oluştu:', error);
    } finally {
        loading.value = false;
    }
};

// Yazıya tıklandığında çalışacak fonksiyon
const openPost = async (id) => {
    try {
        // Tıklanan yazının detaylarını (ve varsa yorumlarını) API'den çek
        const response = await axios.get(`/api/posts/${id}`);
        selectedPost.value = response.data;
        // Sayfanın en üstüne kaydır
        window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch (error) {
        console.error('Yazı detayı çekilirken hata oluştu:', error);
    }
};

onMounted(() => {
    fetchPosts();
});
</script>