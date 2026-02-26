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
                  <h6
                    :class="card.isCurrency ? 'currency-value' : ''"
                    :style="card.isCurrency ? { fontSize: dynamicFontSize(card.value) } : {}"
                  >
                    {{ card.isCurrency ? `KES ${formatAmount(card.value)}` : card.value ?? 0 }}
                  </h6>

                  <!-- Ledger button for monetary cards (landlord only) -->
                  <button
                    v-if="card.isCurrency && userRole === 'landlord'"
                    @click="openLedgerModal(card.title)"
                    class="btn btn-sm btn-outline-primary mt-1"
                  >
                    View Ledger
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Ledger Modal (Landlord Only) -->
    <b-modal
      v-if="userRole === 'landlord'"
      v-model="showLedgerModal"
      title="Ledger Entries"
      size="lg"
      centered
    >
      <div>
        <select v-model="selectedPropertyId" class="form-select mb-2">
          <option value="">All Properties</option>
          <option
            v-for="prop in properties"
            :key="prop.id"
            :value="prop.id"
          >
            {{ prop.name }}
          </option>
        </select>

        <table class="table table-sm table-striped">
          <thead>
            <tr>
              <th>Date</th>
              <th>Property</th>
              <th>Unit</th>
              <th>Tenant</th>
              <th>Type</th>
              <th>Amount</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="entry in filteredLedger" :key="entry.id">
              <td>{{ new Date(entry.created_at).toLocaleDateString() }}</td>
              <td>{{ entry.property_name }}</td>
              <td>{{ entry.unit_name || '-' }}</td>
              <td>{{ entry.tenant_name || '-' }}</td>
              <td>{{ entry.entry_type }}</td>
              <td>KES {{ formatAmount(entry.amount) }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </b-modal>
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
  components: { Master },
  data() {
    return {
      currentYear: '',
      user: {},
      currentUser: {},
      userRole: null,
      stats: {},
      properties: [],
      showLedgerModal: false,
      selectedPropertyId: '',
      ledgerEntries: [],
      badgeClasses: [
        'text-success', 'text-danger', 'text-primary', 'text-info', 'text-warning', 'text-muted'
      ],
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
          { title: 'Total Due', value: this.stats.kpis?.total_due, icon: 'bi-cash-stack', color: 'primary', isCurrency: true },
          { title: 'Total Paid', value: this.stats.kpis?.total_paid, icon: 'bi-cash-coin', color: 'success', isCurrency: true },
          { title: 'Pending Payments', value: this.stats.kpis?.pending_payments, icon: 'bi-hourglass', color: 'warning', isCurrency: true },
          { title: 'Collection Rate', value: this.stats.kpis?.collection_rate + '%', icon: 'bi-percent', color: 'info' },
          { title: 'Draft Invoices', value: this.stats.kpis?.draft_invoices, icon: 'bi-check-circle', color: 'primary' },
          { title: 'Fully Paid Invoices', value: this.stats.kpis?.paid_invoices, icon: 'bi-check-circle', color: 'success' },
          { title: 'Partially Paid Invoices', value: this.stats.kpis?.partial_invoices, icon: 'bi-hourglass-split', color: 'warning' },
          { title: 'Overdue Invoices', value: this.stats.kpis?.overdue_invoices, icon: 'bi-exclamation-triangle', color: 'danger' },
          { title: 'Tickets Open', value: this.stats.tickets_open, icon: 'bi-circle', color: 'warning' },
          { title: 'Tickets In Progress', value: this.stats.tickets_in_progress, icon: 'bi-hourglass-split', color: 'info' },
          { title: 'Tickets Resolved', value: this.stats.tickets_resolved, icon: 'bi-check-circle', color: 'success' },
        ],
        caretaker: [
          { title: 'Assigned Units', value: this.stats.units, icon: 'bi-house-gear', color: 'info' },
          { title: 'Occupied', value: this.stats.rented, icon: 'bi-house-check', color: 'success' },
          { title: 'Vacant', value: this.stats.vacant, icon: 'bi-house-dash', color: 'danger' },
          { title: 'Pending Payments', value: this.stats.kpis?.pending_payments, icon: 'bi-hourglass', color: 'warning', isCurrency: true },
          { title: 'Tickets Open', value: this.stats.tickets_open, icon: 'bi-circle', color: 'warning' },
          { title: 'Tickets In Progress', value: this.stats.tickets_in_progress, icon: 'bi-hourglass-split', color: 'info' },
          { title: 'Tickets Resolved', value: this.stats.tickets_resolved, icon: 'bi-check-circle', color: 'success' },
        ],
        tenant: [
          { title: 'My Unit', value: this.stats.unit, icon: 'bi-house', color: 'primary' },
          { title: 'Rent Status', value: this.stats.rent_status, icon: 'bi-cash-coin', color: 'success', isCurrency: true },
          { title: 'Pending Payments', value: this.stats.kpis?.pending_payments, icon: 'bi-hourglass', color: 'warning', isCurrency: true },
          { title: 'Open Requests', value: this.stats.requests, icon: 'bi-circle', color: 'warning' },
          { title: 'Tickets In Progress', value: this.stats.tickets_in_progress, icon: 'bi-hourglass-split', color: 'info' },
          { title: 'Tickets Resolved', value: this.stats.tickets_resolved, icon: 'bi-check-circle', color: 'success' },
          { title: 'Lease Start', value: this.stats.lease_start || 'N/A', icon: 'bi-calendar-check', color: 'info' },
          { title: 'Lease End', value: this.stats.lease_end || 'N/A', icon: 'bi-calendar-x', color: 'danger' },
        ],
      };

      return (cards[this.userRole] || []).filter(card =>
        card.value !== null && card.value !== undefined && card.value !== 'N/A'
      );
    },

    filteredLedger() {
      if (!this.selectedPropertyId) return this.ledgerEntries;
      return this.ledgerEntries.filter(entry => entry.property_id === this.selectedPropertyId);
    }
  },
  methods: {
    fetchDashboardStats() {
      axios.get('/api/dashboard/stats')
        .then(response => {
          this.stats = response.data.stats || {};
          axios.get('/api/ledger')
            .then(res => { this.stats.kpis = res.data.kpis || {}; })
            .catch(() => toast.fire({ icon: 'error', title: 'Failed to load KPIs' }));
        })
        .catch(() => toast.fire({ icon: 'error', title: 'Failed to load dashboard stats' }));
    },

    openLedgerModal() {
      // HARD STOP: extra safety
      if (this.userRole !== 'landlord') {
        return;
      }

      this.selectedPropertyId = '';
      this.showLedgerModal = false;

      axios.get(`/api/landlords/${this.current_user_id}/ledger`)
        .then(res => {
          this.ledgerEntries = res.data.ledger_entries.map(entry => ({
            ...entry,
            property_name: entry.property?.name || 'N/A',
            unit_name: entry.unit?.name || null,
            tenant_name: entry.tenant?.name || null,
          }));

          this.showLedgerModal = true;
        })
        .catch(() =>
          toast.fire({ icon: 'error', title: 'Failed to load ledger entries' })
        );
    },
    fetchProperties(landlordId) {
      axios.get(`/api/landlords/${landlordId}/properties`)
        .then(res => {
          this.properties = res.data.properties || [];
        })
        .catch(() => toast.fire({ icon: 'error', title: 'Failed to load properties' }));
    },

    formatAmount(value) {
      if (!value) return '0';
      return Number(value).toLocaleString('en-KE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    },

    dynamicFontSize(value) {
      if (!value) return '1rem';
      const length = String(Math.floor(value)).length;
      if (length <= 6) return '1rem';
      if (length <= 9) return '0.85rem';
      if (length <= 12) return '0.7rem';
      return '0.6rem';
    },

    navigateTo(location) { this.$router.push(location); },
    getRandomBadgeClass() { return this.badgeClasses[Math.floor(Math.random() * this.badgeClasses.length)]; },
    getCurrentYear() { this.currentYear = new Date().getFullYear(); },
  },
  mounted() {
    const storedUser = JSON.parse(localStorage.getItem('user')) || {};
    this.user = storedUser;
    this.currentUser = storedUser;
    this.userRole = this.user.role;
    this.current_user_id = storedUser.id;
    this.current_user = `${storedUser.first_name || ''} ${storedUser.last_name || ''}`.trim();
    this.getCurrentYear();
    this.fetchDashboardStats();
    
    if (this.userRole === 'landlord') {
      this.fetchProperties(this.current_user_id); // pass user id
    }
  },

};
</script>

<style scoped>
.card { transition: transform 0.2s; }
.card:hover { transform: scale(1.02); }
.bg-light { background-color: rgba(255, 255, 255, 0.8); }

.currency-value {
  font-weight: 600;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>