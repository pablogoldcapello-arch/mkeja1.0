import { createRouter, createWebHistory } from 'vue-router';
// import Index from '../views/Index.vue';
import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Home from '../views/Home.vue'
import Users from '../views/Users.vue'
import Properties from '../views/Properties.vue'
import Listings from '../views/Listings.vue'
import Landlords from '../views/Landlords.vue'
import Caretakers from '../views/Caretakers.vue'
import ServiceProviders from '../views/ServiceProviders.vue'
import Tenants from '../views/Tenants.vue'
import Units from '../views/Units.vue'
import AwaitingInvoicing from '../views/AwaitingInvoicing.vue'
import PendingTickets from '../views/PendingTickets.vue'
import ClosedTickets from '../views/ClosedTickets.vue'

const routes = [
    { path: '/', name: 'index', component: Login },
    { path: '/login', name: 'login', component: Login },
    { path: '/register', name: 'register', component: Register },
    { path: '/dashboard', name: 'dashboard', component: Home },
    { path: '/users', name: 'users', component: Users },
    { path: '/properties', name: 'properties', component: Properties },
    { path: '/property-listings', name: 'listings', component: Listings },
    { path: '/landlords', name: 'landlords', component: Landlords },
    { path: '/caretakers', name: 'caretakers', component: Caretakers },
    { path: '/service-providers', name: 'serviceproviders', component: ServiceProviders },
    { path: '/tenants', name: 'tenants', component: Tenants },
    { path: '/units/:id', name: 'units', component: Units },
    { path: '/awaitinginvoicing', name: 'awaitinginvoicing', component: AwaitingInvoicing },
    { path: '/pendingtickets', name: 'pendingtickets', component: PendingTickets },
    { path: '/closedtickets', name: 'closedtickets', component: ClosedTickets },
];

const router = createRouter({
    history: createWebHistory('/'),
    routes,
});

export default router;
