<template>
    <div class="min-h-screen bg-gray-50">
        <nav class="bg-indigo-600 shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <router-link to="/" class="text-white text-2xl font-extrabold tracking-wider">
                            Eterna Blog
                        </router-link>
                    </div>
                    <div class="flex items-center space-x-4">
                        <router-link to="/" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Ana Sayfa</router-link>

                        <router-link v-if="currentUser" to="/create-post" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Yazı Ekle</router-link>

                        <template v-if="!currentUser">
                            <router-link to="/login" class="text-indigo-100 hover:text-white px-3 py-2 rounded-md text-sm font-medium transition-colors">Giriş Yap</router-link>
                            <router-link to="/register" class="bg-white text-indigo-600 hover:bg-indigo-50 px-4 py-2 rounded-md text-sm font-bold shadow-sm transition-colors">Kayıt Ol</router-link>
                        </template>

                        <template v-else>
                            <span class="text-indigo-200 text-sm font-medium px-3 py-2 border-l border-indigo-500 pl-4">{{ currentUser.email }}</span>
                            <button @click="logout" class="bg-red-500 text-white hover:bg-red-600 px-4 py-2 rounded-md text-sm font-bold shadow-sm transition-colors cursor-pointer">Çıkış Yap</button>
                        </template>
                    </div>
                </div>
            </div>
        </nav>

        <main>
            <router-view></router-view>
        </main>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';

const currentUser = ref(null);

// Sayfa yüklendiğinde kimliği kontrol et
const checkAuth = async () => {
    const token = localStorage.getItem('auth_token');
    if (token) {
        // Token varsa, sayfadaki TıM Axios isteklerine otomatik olarak yetkiyi ekle
        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        try {
            // Backend'den giriş yapan kullanıcının bilgilerini çek
            const response = await axios.get('/api/user');
            currentUser.value = response.data;
        } catch (error) {
            // Token geçersizse veya süresi dolmuşsa temizle
            localStorage.removeItem('auth_token');
            delete axios.defaults.headers.common['Authorization'];
            currentUser.value = null;
        }
    }
};

// Çıkış Yapma Fonksiyonu
const logout = async () => {
    try {
        await axios.post('/api/logout');
    } catch (error) {
        console.error('Çıkış yaparken hata:', error);
    } finally {
        localStorage.removeItem('auth_token');
        delete axios.defaults.headers.common['Authorization'];
        currentUser.value = null;
        window.location.href = '/login'; // Çıkış yapınca giriş sayfasına gönder
    }
};

onMounted(() => {
    checkAuth();
});
</script>