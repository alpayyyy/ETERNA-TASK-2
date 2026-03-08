<template>
    <div class="max-w-md mx-auto mt-16 bg-white p-8 border border-gray-100 rounded-xl shadow-lg">
        <h2 class="text-3xl font-extrabold text-center text-gray-900 mb-8">Kayıt Ol</h2>
        
        <Form @submit="onSubmit" :validation-schema="schema" v-slot="{ errors, isSubmitting }">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Ad</label>
                    <Field name="name" type="text" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all" :class="{'border-red-500 ring-1 ring-red-500': errors.name}" />
                    <ErrorMessage name="name" class="text-red-500 text-xs mt-1 block" />
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2">Soyad</label>
                    <Field name="surname" type="text" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all" :class="{'border-red-500 ring-1 ring-red-500': errors.surname}" />
                    <ErrorMessage name="surname" class="text-red-500 text-xs mt-1 block" />
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Telefon</label>
                <Field name="phone" v-slot="{ field }">
                    <input v-bind="field" v-mask="'0 (###) ### ## ##'" type="tel" placeholder="0 (555) 123 45 67" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all" :class="{'border-red-500 ring-1 ring-red-500': errors.phone}" />
                </Field>
                <ErrorMessage name="phone" class="text-red-500 text-xs mt-1 block" />
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">E-mail</label>
                <Field name="email" type="email" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all" :class="{'border-red-500 ring-1 ring-red-500': errors.email}" />
                <ErrorMessage name="email" class="text-red-500 text-xs mt-1 block" />
            </div>

            <div class="mb-8">
                <label class="block text-gray-700 text-sm font-bold mb-2">Şifre</label>
                <Field name="password" type="password" class="w-full px-4 py-2 bg-gray-50 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none transition-all" :class="{'border-red-500 ring-1 ring-red-500': errors.password}" />
                <ErrorMessage name="password" class="text-red-500 text-xs mt-1 block" />
            </div>

            <button type="submit" :disabled="isSubmitting" class="w-full bg-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-indigo-700 transition-colors shadow-md disabled:opacity-70 flex justify-center items-center">
                <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                {{ isSubmitting ? 'Kaydediliyor...' : 'Kayıt Ol' }}
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

// Yup ile Form Doğrulama Kuralları
const schema = yup.object({
    name: yup.string().required('Ad alanı zorunludur.'),
    surname: yup.string().required('Soyad alanı zorunludur.'),
    phone: yup.string().required('Telefon numarası zorunludur.').min(17, 'Geçerli bir telefon numarası giriniz.'),
    email: yup.string().required('E-mail alanı zorunludur.').email('Geçerli bir e-mail adresi giriniz.'),
    password: yup.string().required('Şifre zorunludur.').min(6, 'Şifre en az 6 karakter olmalıdır.'),
});

// Form Gönderildiğinde Çalışacak Fonksiyon
const onSubmit = async (values, { setErrors }) => {
    try {
        // Telefon numarasındaki parantez ve boşlukları temizleyip API'ye gönderiyoruz
        const payload = {
            ...values,
            phone: values.phone.replace(/\D/g, '') 
        };
        
        // Sanctum/Fortify varsayılan register rotasına istek atıyoruz
        await axios.post('/api/register', payload);
        
        alert('Kayıt başarıyla tamamlandı! Giriş sayfasına yönlendiriliyorsunuz.');
        router.push('/login');
    } catch (error) {
        if (error.response && error.response.data.errors) {
            setErrors(error.response.data.errors); // Backend'den gelen hataları formda göster
        } else {
            alert('Kayıt olurken bir hata meydana geldi.');
        }
    }
};
</script>