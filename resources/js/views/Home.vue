<template>
  <Master>
    <section class="section dashboard">
      <div class="row">
        <div
          v-for="(card, index) in dashboardCards"
          :key="index"
          class="col-xxl-2 col-md-3 col-sm-4 mb-3"
        >
          <div class="card info-card shadow-sm">
            <div class="card-body">
              <h5 class="card-title" :class="`text-${card.color}`">
                {{ card.title }}
              </h5>

              <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center bg-light">
                  <i :class="`bi ${card.icon} text-${card.color}`"></i>
                </div>
                <div class="ps-3">
                  <h6>{{ card.value ?? 0 }}</h6>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </section>

  </Master>
</template>

<script>
import Master from '@/components/Master.vue';
import axios from 'axios';
import Swal from 'sweetalert2';

const toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000
});

export default {
  name: 'Home',
  components: {
    Master,
  },
  data() {
    return {
      currentYear: '',
      user: {},
      currentUser: {},
      userRole: null,
      stats: {},
      properties: [],
      openproperties: [],
      closedproperties: [],
      users: [],
      badgeClasses: [
        'text-success',
        'text-danger',
        'text-primary',
        'text-info',
        'text-warning',
        'text-muted',
      ],
      dashboardactivities: [],
      userdashboardactivities: [],
      pmsPropertyCount: 0,
      pmsvacantpropertycount: 0,
      pmsrentedpropertycount: 0,
      userscount: 0,
      landlordscount: 0,
      tenantscount: 0,
      rentingTenantscount: 0,
      vacatedTenantscount: 0,
      awaitingInvoicingCount: 0,
      awaitingSettlingCount: 0,
      settledInvoicesCount: 0,
    };
  },
  computed: {
    dashboardCards() {
      const cards = {
        admin: [
          { title: 'Total Users', value: this.stats.users, icon: 'bi-people', color: 'primary' },
          { title: 'Landlords', value: this.stats.landlords, icon: 'bi-person-lines-fill', color: 'info' },
          { title: 'Tenants', value: this.stats.tenants, icon: 'bi-people', color: 'secondary' },
          { title: 'Properties', value: this.stats.properties, icon: 'bi-building', color: 'success' },
          { title: 'Rented Units', value: this.stats.rented, icon: 'bi-house-door', color: 'warning' },
          { title: 'Vacant Units', value: this.stats.vacant, icon: 'bi-box-arrow-right', color: 'danger' },
          { title: 'Tickets Open', value: this.stats.tickets_open, icon: 'bi-circle', color: 'warning' },
          { title: 'Tickets In Progress', value: this.stats.tickets_in_progress, icon: 'bi-hourglass-split', color: 'info' },
          { title: 'Tickets Resolved', value: this.stats.tickets_resolved, icon: 'bi-check-circle', color: 'success' },
        ],

        landlord: [
          { title: 'My Properties', value: this.stats.properties, icon: 'bi-building', color: 'success' },
          { title: 'Rented Units', value: this.stats.rented, icon: 'bi-house-door', color: 'warning' },
          { title: 'Vacant Units', value: this.stats.vacant, icon: 'bi-box-arrow-right', color: 'danger' },
          { title: 'Tenants', value: this.stats.tenants, icon: 'bi-people', color: 'secondary' },

          // 🔑 Ledger / KPI cards
          { title: 'Total Due', value: this.stats.kpis?.total_due, icon: 'bi-cash-stack', color: 'primary' },
          { title: 'Total Paid', value: this.stats.kpis?.total_paid, icon: 'bi-cash-coin', color: 'success' },
          { title: 'Collection Rate', value: this.stats.kpis?.collection_rate + '%', icon: 'bi-percent', color: 'info' },
          { title: 'Paid Invoices', value: this.stats.kpis?.paid_invoices, icon: 'bi-check-circle', color: 'success' },
          { title: 'Partial Invoices', value: this.stats.kpis?.partial_invoices, icon: 'bi-hourglass-split', color: 'warning' },
          { title: 'Overdue Invoices', value: this.stats.kpis?.overdue_invoices, icon: 'bi-exclamation-triangle', color: 'danger' },

          { title: 'Tickets Open', value: this.stats.tickets_open, icon: 'bi-circle', color: 'warning' },
          { title: 'Tickets In Progress', value: this.stats.tickets_in_progress, icon: 'bi-hourglass-split', color: 'info' },
          { title: 'Tickets Resolved', value: this.stats.tickets_resolved, icon: 'bi-check-circle', color: 'success' },
        ],

        caretaker: [
          { title: 'Assigned Units', value: this.stats.units, icon: 'bi-house-gear', color: 'info' },
          { title: 'Occupied', value: this.stats.rented, icon: 'bi-house-check', color: 'success' },
          { title: 'Vacant', value: this.stats.vacant, icon: 'bi-house-dash', color: 'danger' },
          { title: 'Tickets Open', value: this.stats.tickets_open, icon: 'bi-circle', color: 'warning' },
          { title: 'Tickets In Progress', value: this.stats.tickets_in_progress, icon: 'bi-hourglass-split', color: 'info' },
          { title: 'Tickets Resolved', value: this.stats.tickets_resolved, icon: 'bi-check-circle', color: 'success' },
        ],

        service_provider: [
          { title: 'Assigned Jobs', value: this.stats.jobs, icon: 'bi-briefcase', color: 'primary' },
          { title: 'Completed', value: this.stats.completed, icon: 'bi-check-circle', color: 'success' },
          { title: 'Pending', value: this.stats.pending, icon: 'bi-clock', color: 'warning' },
          { title: 'In Progress', value: this.stats.in_progress, icon: 'bi-hourglass-split', color: 'info' },
        ],

        tenant: [
          { title: 'My Unit', value: this.stats.unit, icon: 'bi-house', color: 'primary' },
          { title: 'Rent Status', value: this.stats.rent_status, icon: 'bi-cash-coin', color: 'success' },
          { title: 'Open Requests', value: this.stats.requests, icon: 'bi-circle', color: 'warning' },
          { title: 'Tickets In Progress', value: this.stats.tickets_in_progress, icon: 'bi-hourglass-split', color: 'info' },
          { title: 'Tickets Resolved', value: this.stats.tickets_resolved, icon: 'bi-check-circle', color: 'success' },
          { title: 'Lease Start', value: this.stats.lease_start || 'N/A', icon: 'bi-calendar-check', color: 'info' },
          { title: 'Lease End', value: this.stats.lease_end || 'N/A', icon: 'bi-calendar-x', color: 'danger' },
        ],
      };

      // Remove cards with null, 0, or 'N/A' values for cleaner UI
      return (cards[this.userRole] || []).filter(card => {
        return card.value !== null && card.value !== 0 && card.value !== 'N/A';
      });
    }
  },
  methods: {
    fetchDashboardStats() {
      axios
        .get('/api/dashboard/stats') // existing stats
        .then(response => {
          this.stats = response.data.stats || {};

          // 🔑 Fetch KPI-specific ledger info
          axios.get('/api/ledger')
            .then(res => {
              this.stats.kpis = res.data.kpis || {};
            })
            .catch(err => {
              console.error('Failed to fetch KPIs:', err);
              toast.fire({ icon: 'error', title: 'Failed to load KPIs' });
            });
        })
        .catch(error => {
          console.error('Dashboard stats error:', error);
          toast.fire({ icon: 'error', title: 'Failed to load dashboard stats' });
        });
    },
    navigateTo(location) {
      this.$router.push(location);
    },
    getRandomBadgeClass() {
      const randomIndex = Math.floor(Math.random() * this.badgeClasses.length);
      return this.badgeClasses[randomIndex];
    },
    getRelativeTime(createdAt) {
      const currentTime = new Date();
      const activityTime = new Date(createdAt);
      const differenceInSeconds = (currentTime - activityTime) / 1000;

      if (differenceInSeconds < 60) return `${Math.floor(differenceInSeconds)} seconds ago`;
      const differenceInMinutes = differenceInSeconds / 60;
      if (differenceInMinutes < 60) return `${Math.floor(differenceInMinutes)} mins ago`;
      const differenceInHours = differenceInMinutes / 60;
      if (differenceInHours < 24) return `${Math.floor(differenceInHours)} hrs ago`;
      const differenceInDays = differenceInHours / 24;
      if (differenceInDays < 7) return `${Math.floor(differenceInDays)} days ago`;
      const differenceInWeeks = differenceInDays / 7;
      return `${Math.floor(differenceInWeeks)} weeks ago`;
    },
    goAwaitingInvoicing() { this.$router.push('/awaitinginvoicing') },
    goAwaitingSettling() { this.$router.push('/invoicestosettle') },
    goSettledInvoices() { this.$router.push('/settledinvoices') },
    goProperties() { this.$router.push('/managedproperties') },
    goUsers() { this.$router.push('/all-users') },
    goLandlords() { this.$router.push('/pmslandlords') },
    goTenants() { this.$router.push('/pmstenants') },
    goRentingTenants() { this.$router.push('/pmsrentingtenants') },
    goVacatedTenants() { this.$router.push('/pmsvacatedtenants') },
    goRentedUnits() { this.$router.push('/rentedunits') },
    goVacantUnits() { this.$router.push('/vacantunits') },
    getCurrentYear() {
      this.currentYear = new Date().getFullYear();
    },
    loadLists() {
      axios.get('api/lists')
        .then(response => {
          const data = response.data.lists;
          this.properties = data.properties;
          this.openproperties = data.openproperties;
          this.closedproperties = data.closedproperties;
          this.users = data.users;
          this.pmsPropertyCount = data.pmspropertycount;
          this.pmsvacantpropertycount = data.pmsvacantpropertycount;
          this.pmsrentedpropertycount = data.pmsrentedpropertycount;
          this.userscount = data.userscount;
          this.landlordscount = data.landlordscount;
          this.tenantscount = data.tenantscount;
          this.vacatedTenantscount = data.vacatedtenantscount;
          this.rentingTenantscount = data.rentingtenantscount;
          this.awaitingInvoicingCount = data.allawaitinginvoicing.length;
          this.awaitingSettlingCount = data.invoicestosettle.length;
          this.settledInvoicesCount = data.settledinvoices.length;

          const payload = {
            description: `${this.current_user} visited dashboard page`,
            user_id: this.current_user_id,
          };

          axios.post('/api/activity', payload).catch(() => {
            toast.fire({ icon: 'error', title: 'Failed to log activity' });
          });
        })
        .catch(() => {
          toast.fire({ icon: 'error', title: 'Failed to load dashboard data' });
        });
    },
    getActivities() {
      axios.get('api/dashboardactivities')
        .then(response => {
          this.dashboardactivities = response.data.dashboardactivities;
        })
        .catch(() => {
          toast.fire({ icon: 'error', title: 'Failed to load activities' });
        });
    },
    getUserActivities() {
      axios.get('api/userdashboardactivities/' + this.current_user_id)
        .then(response => {
          this.userdashboardactivities = response.data.userdashboardactivities;
        })
        .catch(() => {
          toast.fire({ icon: 'error', title: 'Failed to load your activities' });
        });
    }
  },
  mounted() {
    const storedUser = JSON.parse(localStorage.getItem('user')) || {};
    this.user = storedUser;
    this.currentUser = storedUser;
    this.userRole = this.user.role;
    this.current_user_id = storedUser.id;
    this.current_user = `${storedUser.first_name || ''} ${storedUser.last_name || ''}`.trim();
    // console.log("rem", this.userRole)
    // this.loadLists();
    // this.getActivities();
    // this.getUserActivities();
    this.getCurrentYear();
    this.fetchDashboardStats();
  }
};
</script>



<style scoped>
.card {
  transition: transform 0.2s;
}

.card:hover {
  transform: scale(1.02);
}

.bg-light {
  background-color: rgba(255, 255, 255, 0.8);
}
</style>