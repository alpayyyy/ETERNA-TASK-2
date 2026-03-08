<template>
    <div class="max-w-md mx-auto mt-20 bg-white p-8 border border-gray-100 rounded-xl shadow-lg">
        <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-8">Giriş Yap</h2>
        
        <Form @submit="onSubmit" :validation-schema="schema" v-slot="{ errors, isSubmitting }">
            
            <div class="mb-5">
                <label class="block text-gray-700 text-sm font-bold mb-2">E-mail veya Telefon Numarası</label>
                <Field name="login_field" type="text" placeholder="ornek@email.com veya 5551234567" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all" :class="{'border-red-500 ring-1 ring-red-500': errors.login_field}" />
                <ErrorMessage name="login_field" class="text-red-500 text-xs mt-1 block" />
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 text-sm font-bold mb-2">Şifre</label>
                <Field name="password" type="password" placeholder="••••••••" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all" :class="{'border-red-500 ring-1 ring-red-500': errors.password}" />
                <ErrorMessage name="password" class="text-red-500 text-xs mt-1 block" />
            </div>

            <button type="submit" :disabled="isSubmitting" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition-colors shadow-md disabled:opacity-70 flex justify-center items-center">
                <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                {{ isSubmitting ? 'Giriş Yapılıyor...' : 'Giriş Yap' }}
            </button>
        </Form>
    </div>
</template>

<script setup>
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import axios from 'axios';
import { useRouter } from 'vue-router';

const router = useRouter();

const schema = yup.object({
    login_field: yup.string().required('E-mail veya telefon alanı zorunludur.'),
    password: yup.string().required('Şifre zorunludur.')
});

const onSubmit = async (values, { setErrors }) => {
    try {
        await axios.get('/sanctum/csrf-cookie');
        
        // BACKEND'İN BEKLEDİĞİ GİBİ 'login' İSMİYLE GÖNDERİYORUZ
        const payload = {
            login: values.login_field, 
            password: values.password
        };
        
        const response = await axios.post('/api/login', payload);
        
        if (response.data.token) {
            localStorage.setItem('auth_token', response.data.token);
            axios.defaults.headers.common['Authorization'] = `Bearer ${response.data.token}`;
        }
        
        alert('Başarıyla giriş yaptınız!');
        window.location.href = '/';
        
    } catch (error) {
        console.error("Giriş Hatası Detayı:", error);
        
        if (error.response && (error.response.status === 422 || error.response.status === 401)) {
            const backendErrors = error.response.data.errors || {};
            const message = error.response.data.message || 'Bilgileriniz hatalı.';
            
            // BACKEND'DEN GELEN 'login' HATASINI EKRANDAKİ İNPUTUMUZA YANSITIYORUZ
            const displayError = backendErrors.login || backendErrors.email || backendErrors.phone || [message];
            setErrors({ login_field: displayError[0] });
        } else {
            alert('Sunucu ile iletişim kurulamadı. Backend ayakta mı kontrol edin.');
        }
    }
};
</script>