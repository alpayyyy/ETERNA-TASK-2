import { createRouter, createWebHistory } from 'vue-router';
import Home from '../views/Home.vue';
import Register from '../views/Register.vue';
import Login from '../views/Login.vue';
import CreatePost from '../views/CreatePost.vue';

const routes = [
    { path: '/', name: 'Home', component: Home },
    { path: '/register', name: 'Register', component: Register },
    { path: '/login', name: 'Login', component: Login },
    { path: '/create-post', name: 'CreatePost', component: CreatePost }
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

export default router;