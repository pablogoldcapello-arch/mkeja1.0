import { createRouter, createWebHistory } from 'vue-router';

import Login from '../views/Login.vue';
import Register from '../views/Register.vue';
import Home from '../views/Home.vue';
import Users from '../views/Users.vue';
import Properties from '../views/Properties.vue';
import LandlordProperties from '../views/LandlordProperties.vue';
import Listings from '../views/Listings.vue';
import LandlordListings from '../views/LandlordListings.vue';
import Landlords from '../views/Landlords.vue';
import Caretakers from '../views/Caretakers.vue';
import ServiceProviders from '../views/ServiceProviders.vue';
import Tenants from '../views/Tenants.vue';
import PropertyTenants from '../views/PropertyTenants.vue';
import Units from '../views/Units.vue';
import TenantInvoices from '../views/TenantInvoices.vue';
import DraftInvoices from '../views/DraftInvoices.vue';
import PartialInvoices from '../views/PartialInvoices.vue';
import PaidInvoices from '../views/PaidInvoices.vue';
import ProviderInvoices from '../views/ProviderInvoices.vue';
import PropertyInvoices from '../views/PropertyInvoices.vue';
import PendingTickets from '../views/PendingTickets.vue';
import ClosedTickets from '../views/ClosedTickets.vue';
import ActivityLogs from '../views/ActivityLogs.vue';
import Profile from '../views/Profile.vue';

const routes = [
  // Public routes
  { path: '/', name: 'index', component: Login },
  { path: '/login', name: 'login', component: Login },
  { path: '/register', name: 'register', component: Register },

  // Protected routes
  { path: '/dashboard', name: 'dashboard', component: Home, meta: { requiresAuth: true } },
  { path: '/users', name: 'users', component: Users, meta: { requiresAuth: true } },
  { path: '/properties', name: 'properties', component: Properties, meta: { requiresAuth: true } },
  { path: '/landlord-properties', name: 'landlordproperties', component: LandlordProperties, meta: { requiresAuth: true } },
  { path: '/property-listings', name: 'listings', component: Listings, meta: { requiresAuth: true } },
  { path: '/landlord-listings', name: 'landlordlistings', component: LandlordListings, meta: { requiresAuth: true } },
  { path: '/landlords', name: 'landlords', component: Landlords, meta: { requiresAuth: true } },
  { path: '/caretakers', name: 'caretakers', component: Caretakers, meta: { requiresAuth: true } },
  { path: '/service-providers', name: 'serviceproviders', component: ServiceProviders, meta: { requiresAuth: true } },
  { path: '/tenants', name: 'tenants', component: Tenants, meta: { requiresAuth: true } },
  { path: '/units/:id', name: 'units', component: Units, meta: { requiresAuth: true } },
  { path: '/tenants/:id', name: 'proptenants', component: PropertyTenants, meta: { requiresAuth: true } },
  { path: '/draft-invoices', name: 'draft-invoices', component: DraftInvoices, meta: { requiresAuth: true } },
  { path: '/partial-invoices', name: 'partial-invoices', component: PartialInvoices, meta: { requiresAuth: true } },
  { path: '/paid-invoices', name: 'paid-invoices', component: PaidInvoices, meta: { requiresAuth: true } },
  { path: '/tenant-invoices', name: 'tenant-invoices', component: TenantInvoices, meta: { requiresAuth: true } },
  { path: '/provider-invoices', name: 'provider-invoices', component: ProviderInvoices, meta: { requiresAuth: true } },
  { path: '/property-invoices/:id', name: 'property-invoices', component: PropertyInvoices, meta: { requiresAuth: true } },
  { path: '/pendingtickets', name: 'pendingtickets', component: PendingTickets, meta: { requiresAuth: true } },
  { path: '/closedtickets', name: 'closedtickets', component: ClosedTickets, meta: { requiresAuth: true } },
  { path: '/activitylogs', name: 'activitylogs', component: ActivityLogs, meta: { requiresAuth: true } },
  { path: '/profile', name: 'profile', component: Profile, meta: { requiresAuth: true } },
];

const router = createRouter({
  history: createWebHistory('/'),
  routes,
});

// 🔐 Global Auth Guard
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token');

  if (to.meta.requiresAuth && !token) {
    next({ path: '/login', replace: true });
  } else {
    next();
  }
});

export default router;
