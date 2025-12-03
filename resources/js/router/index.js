import { createRouter, createWebHistory } from 'vue-router';
// import Index from '../views/Index.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Home from '../views/Home.vue'
import Users from '../views/Users.vue'
import Properties from '../views/Properties.vue'
import Listings from '../views/Listings.vue'
import Landlords from '../views/Landlords.vue'

const routes = [
    { path: '/', name: 'index', component: Login },
    { path: '/login', name: 'login', component: Login },
    { path: '/register', name: 'register', component: Register },
    { path: '/dashboard', name: 'dashboard', component: Home },
    { path: '/users', name: 'users', component: Users },
    { path: '/properties', name: 'properties', component: Properties },
    { path: '/property-listings', name: 'listings', component: Listings },
    { path: '/landlords', name: 'landlords', component: Landlords },
];

const router = createRouter({
    history: createWebHistory('/'),
    routes,
});

export default router;
