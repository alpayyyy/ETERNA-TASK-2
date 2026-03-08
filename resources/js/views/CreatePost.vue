<template>
    <div class="max-w-3xl mx-auto mt-12 bg-white p-8 border border-gray-100 rounded-xl shadow-lg">
        <h2 class="text-3xl font-extrabold text-gray-900 mb-8 border-b pb-4">Yeni Yazı Ekle</h2>
        
        <form @submit.prevent="submitPost">
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Yazı Başlığı</label>
                <input v-model="form.title" type="text" placeholder="Harika bir başlık girin..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2">Kategoriler (Arayarak Seçebilirsiniz)</label>
                <v-select 
                    v-model="form.categories" 
                    :options="categories" 
                    label="name" 
                    :reduce="category => category.id" 
                    multiple 
                    placeholder="Kategori ara veya seç..."
                    class="bg-gray-50 text-base"
                ></v-select>
                <p class="text-xs text-gray-400 mt-1">Birden fazla kategori seçebilirsiniz.</p>
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 text-sm font-bold mb-2">Yazı İçeriği</label>
                <textarea v-model="form.content" rows="8" placeholder="Aklınızdakileri buraya dökün..." class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all" required></textarea>
            </div>

            <button type="submit" :disabled="isSubmitting" class="w-full bg-indigo-600 text-white font-bold py-4 px-4 rounded-lg hover:bg-indigo-700 transition-colors shadow-md disabled:opacity-70">
                {{ isSubmitting ? 'Yayınlanıyor...' : 'Yazıyı Yayınla' }}
            </button>
        </form>
    </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();
const isSubmitting = ref(false);
const categories = ref([]);

const form = ref({
    title: '',
    content: '',
    categories: [],
    status: 'yayinda'
});

// Sayfa yüklendiğinde veritabanındaki kategorileri vue-select için çekiyoruz
const fetchCategories = async () => {
    try {
        const response = await axios.get('/api/categories');
        categories.value = response.data;
    } catch (error) {
        console.error('Kategoriler çekilemedi:', error);
    }
};

const submitPost = async () => {
    isSubmitting.value = true;
    try {
        // Tarayıcı hafızasındaki token'ı manuel olarak alıyoruz
        const token = localStorage.getItem('auth_token'); 

        await axios.post('/api/posts', form.value, {
            headers: {
                Authorization: `Bearer ${token}` // Backend'e "Ben Adminim" kartını gösteriyoruz
            }
        });

        alert('Yazınız başarıyla yayınlandı!');
        window.location.href = '/'; // Sorunsuz biterse ana sayfaya temiz bir dönüş
        
    } catch (error) {
        console.error('Yazı ekleme hatası detayları:', error.response?.data || error);
        
        // Hatanın tam olarak backend'den nasıl geldiğini ekrana basıyoruz
        const errorMsg = error.response?.data?.message || 'Yazı eklenirken bir hata oluştu.';
        alert('Hata: ' + errorMsg);
    } finally {
        isSubmitting.value = false;
    }
};

onMounted(() => {
    fetchCategories();
});
</script>

<style>
/* vue-select için ufak bir tasarım ayarı */
.v-select .vs__dropdown-toggle {
    padding: 8px;
    border-radius: 0.5rem;
    border-color: #e5e7eb;
}
</style>