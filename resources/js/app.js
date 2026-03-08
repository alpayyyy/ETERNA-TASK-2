import './bootstrap';
import { createApp } from 'vue';
import App from './App.vue';

// Router'ı içe aktarıyoruz
import router from './router';

// Kurduğumuz kütüphaneleri içe aktarıyoruz
import VueTheMask from 'vue-the-mask';
import vSelect from 'vue-select';
import 'vue-select/dist/vue-select.css'; // Seçim kutusunun tasarımı için gerekli

const app = createApp(App);

// Kütüphaneleri Vue uygulamamıza entegre ediyoruz
app.use(router);
app.use(VueTheMask);
app.component('v-select', vSelect);

app.mount('#app');