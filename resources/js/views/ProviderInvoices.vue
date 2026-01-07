<template>
    <Master>
        <section class="section dashboard">
          <div class="row">
    
                <!-- Top Selling -->
                <div class="col-12">
                  <div class="card top-selling overflow-auto">
    
                    <div class="filter">
                      <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                          <h6>Filter</h6>
                        </li>
    
                        <li>
                            <router-link to="/awaitinginvoicing/" custom v-slot="{ href, navigate, isActive }">
                            <a
                                :href="href"
                                :class="{ active: isActive }"
                                class="dropdown-item"
                                @click="navigate"
                            >
                            This Month</a>
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/lastmonthawaitinginvoicing" custom v-slot="{ href, navigate, isActive }">
                            <a
                                :href="href"
                                :class="{ active: isActive }"
                                class="dropdown-item"
                                @click="navigate"
                            >
                            Last Month</a>
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/lastninetyawaitinginvoicing" custom v-slot="{ href, navigate, isActive }">
                            <a
                                :href="href"
                                :class="{ active: isActive }"
                                class="dropdown-item"
                                @click="navigate"
                            >
                            Last 90 Days</a>
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/quarterawaitinginvoicing" custom v-slot="{ href, navigate, isActive }">
                            <a
                                :href="href"
                                :class="{ active: isActive }"
                                class="dropdown-item"
                                @click="navigate"
                            >
                            This Quarter</a>
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/yearawaitinginvoicing" custom v-slot="{ href, navigate, isActive }">
                            <a
                                :href="href"
                                :class="{ active: isActive }"
                                class="dropdown-item"
                                @click="navigate"
                            >
                            This Year</a>
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/lastyearawaitinginvoicing" custom v-slot="{ href, navigate, isActive }">
                            <a
                                :href="href"
                                :class="{ active: isActive }"
                                class="dropdown-item"
                                @click="navigate"
                            >
                            Last Year</a>
                            </router-link>
                        </li>
                        <li>
                            <router-link to="/allawaitinginvoicing" custom v-slot="{ href, navigate, isActive }">
                            <a
                                :href="href"
                                :class="{ active: isActive }"
                                class="dropdown-item"
                                @click="navigate"
                            >
                            All Time</a>
                            </router-link>
                        </li>

                      </ul>
                    </div>
    
                      <div class="card-body pb-0">
                        <h5 class="card-title">My Invoices <span>| {{ awaitinginvoicing.length }} unpaid invoices</span></h5>
                        <p class="card-text">
                          <div class="row">
                            <div class="col d-flex">
                              <button v-if="awaitinginvoicing.length !== 0" class="me-2" @click="exportToExcel">Export</button>

                            </div>
                            <div class="col-auto d-flex justify-content-end">
                              <div class="btn-group" role="group">
                                <button
                                  id="btnGroupDrop1"
                                  type="button"
                                  style="background-color: darkgreen; border-color: darkgreen;"
                                  class="btn btn-sm btn-primary rounded-pill dropdown-toggle"
                                  data-toggle="dropdown"
                                  data-bs-toggle="dropdown"
                                  aria-haspopup="true"
                                  aria-expanded="false"
                                >
                                  <i class="ri-add-line"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                  <a @click="navigateTo('/awaitinginvoicing')" class="dropdown-item" href="#">
                                    <i class="ri-file-list-2-fill mr-2"></i>Awaiting Invoicing
                                  </a>
                                  <a @click="navigateTo('/invoicestosettle')" class="dropdown-item" href="#">
                                    <i class="ri-file-edit-fill mr-2"></i>Invoices to Settle
                                  </a>
                                  <a @click="navigateTo('/settledinvoices')" class="dropdown-item" href="#">
                                    <i class="ri-bank-card-fill mr-2"></i>Settled Invoices
                                  </a>
                                  <a @click="navigateTo('/managedproperties')" class="dropdown-item" href="#">
                                    <i class="ri-building-fill mr-2"></i>Properties
                                  </a>
                                  <a @click="navigateTo('/tenants')" class="dropdown-item" href="#">
                                    <i class="ri-user-fill mr-2"></i>Tenants
                                  </a>
                                  <a @click="navigateTo('/pmslandlords')" class="dropdown-item" href="#">
                                    <i class="ri-user-fill mr-2"></i>Landlords
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </p>

                        <!-- Display table when statements.length is not zero -->
                        <div>
                          <table id="AllStatementsTable" class="table table-borderless">
                            <thead>
                              <tr>
                                <th scope="col">Rent Month / Services</th>
                                <th scope="col">Amount Due</th>
                                <th scope="col">Due Date</th>
                                <th scope="col">Status</th>
                                <th scope="col">Action</th>
                              </tr>
                            </thead>

                            <!-- Spinner while initializing -->
                            <tbody v-if="initializing">
                              <tr>
                                <td colspan="7" class="text-center">
                                  <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                  </div>
                                </td>
                              </tr>
                            </tbody>

                            <!-- Invoice rows -->
                            <tbody v-else>
                              <tr v-for="statement in awaitinginvoicing" :key="statement.id">

                                <!-- Rent month or services -->
                                <td>
                                  <span v-if="statement.type === 'tenant'">{{ statement.rent_month }}</span>
                                  <span v-else-if="statement.type === 'service_provider'">
                                    <span v-for="(service, idx) in JSON.parse(statement.services)" :key="idx" class="badge bg-primary me-1 mb-1">{{ service }}</span>
                                  </span>
                                </td>

                                <!-- Amount Due -->
                                <td>
                                  {{ statement.amount_due ? 'KES ' + statement.amount_due.toLocaleString() : '-' }}
                                </td>


                                <!-- Due date -->
                                <td :class="{'text-danger': isOverdue(statement.due_date)}">
                                  {{ format_date(statement.due_date) }}
                                </td>

                                <!-- Status -->
                                <td>
                                  <span v-if="statement.status === 'draft'" class="badge bg-secondary">
                                    <i class="bi bi-file-earmark-text"></i> Draft
                                  </span>
                                  <span v-else-if="statement.status === 'sent'" class="badge bg-primary">
                                    <i class="bi bi-envelope"></i> Sent
                                  </span>
                                  <span v-else-if="statement.status === 'unpaid'" class="badge bg-info text-dark">
                                    <i class="bi bi-clipboard2-x"></i> Unpaid
                                  </span>
                                  <span v-else-if="statement.status === 'paid'" class="badge bg-success">
                                    <i class="bi bi-clipboard2-check"></i> Paid
                                  </span>
                                  <span v-else-if="statement.status === 'overdue'" class="badge bg-warning text-dark">
                                    <i class="bi bi-clipboard2-x"></i> Overdue
                                  </span>
                                </td>

                                <!-- Action buttons -->
                                <td>
                                  <div class="btn-group" role="group">
                                    <button
                                      type="button"
                                      class="btn btn-sm btn-primary rounded-pill dropdown-toggle"
                                      data-bs-toggle="dropdown"
                                      aria-haspopup="true"
                                      aria-expanded="false"
                                      style="background-color: darkgreen; border-color: darkgreen;"
                                    >
                                      Action
                                    </button>
                                    <div class="dropdown-menu">
                                      <a @click="viewInvoice(statement)" class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>

                                      <!-- Tenant-specific actions -->
                                      <template v-if="statement.type === 'tenant'">
                                        <a v-if="statement.status == 'unpaid'" @click="invoiceTenant(statement)" class="dropdown-item" href="#"><i class="ri-bill-line mr-2"></i>Pay</a>
                                        <!-- <a v-if="statement.status == 'unpaid'" @click="settleTenant(statement.id, statement.tenant_id)" class="dropdown-item" href="#"><i class="ri-check-fill mr-2"></i>Settle</a> -->
                                      </template>

                                      <!-- Service provider-specific actions -->
                                      <template v-else-if="statement.type === 'service_provider'">
                                        <a v-if="statement.status == 'unpaid'" @click="invoiceProvider(statement)" class="dropdown-item" href="#"><i class="ri-bill-line mr-2"></i>Pay</a>
                                      </template>

                                      <!-- Common actions -->
                                      <a @click="editInvoice(statement)" class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                      <a @click="deleteInvoice(statement.id)" class="dropdown-item" href="#"><i class="ri-delete-bin-line mr-2"></i>Delete</a>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>


                          <div>
                            <strong>Total: 
                              Due: {{ formatNumber(calculateTotal('total')) }},
                              Paid: {{ formatNumber(calculateTotal('paid')) }},
                              Bal: {{ formatNumber(calculateTotal('balance')) }}
                            </strong>
                          </div>
                        </div>

                        
                      </div>

                    <!-- Modal -->
                    <div class="modal fade" id="invoiceTenantModal" tabindex="-1" aria-labelledby="invoiceTenantModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="invoiceTenantModalLabel">Pay Invoice</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p>#{{selectedStatement.invoice_number}}</p>
                            <p v-if="selectedStatement && selectedStatement.tenant">
                              <strong>Tenant Name:</strong> {{ selectedStatement.tenant.name }}
                            </p>
                            <p v-else>
                              <strong>Tenant Name:</strong> N/A
                            </p>
                            <p v-if="selectedStatement">
                              <strong>Rent Month:</strong> {{ selectedStatement.rent_month }}
                            </p>
                            <p v-else>
                              <strong>Rent Month:</strong> N/A
                            </p>
                            <p v-if="selectedStatement">
                              <strong>Amount Due:</strong> {{ formatNumber(selectedStatement.amount_due) }}
                            </p>
                            <p v-else>
                              <strong>Amount Due:</strong> N/A
                            </p>
                            <p v-if="selectedStatement && selectedStatement.tenant">
                              <strong>Phone Number:</strong>
                              <input
                                type="tel"
                                class="form-control"
                                v-model="selectedStatement.tenant.phone"
                                placeholder="Enter phone number"
                              >
                            </p>
                            <small class="text-muted">
                              Tenant will receive an M-Pesa prompt on this number
                            </small>
                            <div v-if="errors.phone" class="text-danger">
                              {{ errors.phone }}
                            </div>


                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                              <button type="button" style="background-color: darkgreen; border-color: darkgreen;" :disabled="invoicing" class="btn btn-primary" @click="confirmInvoiceTenant">
                              <span v-if="invoicing">
                                <i class="fa fa-spinner fa-spin"></i> Sending STK Push...
                              </span>
                     <!--          <span v-else-if="mailing">
                                <i class="fa fa-spinner fa-spin"></i> Mailing...
                              </span> -->
                              <span v-else>
                                Send STK Push
                              </span>

                            </button>
                          </div>
                        </div>
                      </div>
                    </div>

                    <!-- View Invoice Modal -->
                    <div
                      class="modal fade"
                      id="viewInvoiceModal"
                      tabindex="-1"
                      aria-labelledby="viewInvoiceModalLabel"
                      aria-hidden="true"
                    >
                      <div class="modal-dialog modal-lg modal-dialog-centered">
                        <div class="modal-content shadow-sm">

                          <!-- Header -->
                          <div class="modal-header bg-light">
                            <h5 class="modal-title fw-bold" id="viewInvoiceModalLabel">
                              Invoice Details
                            </h5>
                            <button
                              type="button"
                              class="btn-close"
                              data-bs-dismiss="modal"
                              aria-label="Close"
                            ></button>
                          </div>

                          <!-- Body -->
                          <div class="modal-body">
                            <!-- Invoice Summary -->
                            <div class="mb-3">
                              <h6 class="text-uppercase text-muted mb-2">Invoice Information</h6>
                              <div class="row">
                                <div class="col-md-6">
                                  <p><strong>Invoice No:</strong> {{ selectedStatement.invoice_number }}</p>
                                  <p><strong>Status:</strong>
                                    <span
                                      class="badge"
                                      :class="{
                                        'bg-secondary': selectedStatement.status === 'unpaid',
                                        'bg-success': selectedStatement.status === 'paid',
                                        'bg-warning': selectedStatement.status === 'pending'
                                      }"
                                    >
                                      {{ selectedStatement.status }}
                                    </span>
                                  </p>
                                </div>
                                <div class="col-md-6">
                                  <p v-show="selectedStatement.type === 'tenant'"><strong>Rent Month:</strong> {{ rentMonth }}</p>
                                  <p><strong>Due Date:</strong> {{ format_date(selectedStatement.due_date) }}</p>
                                  <p><strong>Created On:</strong> {{ format_date(selectedStatement.created_at) }}</p>
                                </div>
                              </div>
                            </div>

                            <hr />

                            <!-- Tenant / Provider Info -->
                            <div class="mb-3">
                              <h6 class="text-uppercase text-muted mb-2">
                                {{ selectedStatement.type === 'tenant' ? 'Tenant Information' : 'Service Provider Information' }}
                              </h6>

                              <!-- Tenant -->
                              <div v-if="selectedStatement.type === 'tenant' && selectedStatement.tenant">
                                <p><strong>Name:</strong> {{ selectedStatement.tenant.name }}</p>
                                <p><strong>Email:</strong> {{ selectedStatement.tenant.email }}</p>
                                <p><strong>Phone:</strong> {{ selectedStatement.tenant.phone }}</p>
                                <p><strong>Address:</strong>
                                  {{ selectedStatement.tenant.address }},
                                  {{ selectedStatement.tenant.city }},
                                  {{ selectedStatement.tenant.county }}
                                </p>
                              </div>

                              <!-- Service Provider -->
                              <div v-if="selectedStatement.type === 'service_provider' && selectedStatement.provider">
                                <p><strong>Name:</strong> {{ selectedStatement.provider.name }}</p>
                                <p><strong>Email:</strong> {{ selectedStatement.provider.email }}</p>
                                <p><strong>Phone:</strong> {{ selectedStatement.provider.phone }}</p>
                              </div>
                            </div>

                            <hr />

                            <!-- Property Info (Tenant only) -->
                            <div v-if="selectedStatement.type === 'tenant'" class="mb-3">
                              <h6 class="text-uppercase text-muted mb-2">Property Information</h6>
                              <p><strong>Property:</strong> {{ selectedStatement.property.title }}</p>
                            </div>

                            <hr />

                            <!-- Amount -->
                            <div class="text-end">
                              <h5>
                                Amount Due:
                                <span class="fw-bold text-primary">
                                  KES {{ Number(selectedStatement.amount_due).toLocaleString() }}
                                </span>
                              </h5>
                            </div>
                          </div>

                          <!-- Footer -->
                          <div class="modal-footer">
                            <button
                              type="button"
                              class="btn btn-outline-secondary"
                              data-bs-dismiss="modal"
                            >
                              Close
                            </button>
                          </div>

                        </div>
                      </div>
                    </div>


                    <!-- Add Invoice Modal -->
                    <div class="modal fade" id="addInvoiceModal" tabindex="-1" role="dialog" aria-labelledby="addInvoiceModalLabel" aria-hidden="true">
                      <div class="modal-dialog" role="document">
                        <div class="modal-content">
                          
                          <!-- Modal Header -->
                          <div class="modal-header">
                            <h5 class="modal-title" id="addInvoiceModalLabel">
                              Create {{ form.type === 'tenant' ? 'Tenant' : 'Service Provider' }} Invoice
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close" @click="closeModal">
                              <span aria-hidden="true">&times;</span>
                            </button>
                          </div>

                          <!-- Modal Body -->
                          <div class="modal-body">

                            <!-- Invoice Type -->
                            <p>
                              <strong>Invoice Type:*</strong>
                              <select v-model="form.type" class="form-control">
                                <option value="tenant">Tenant</option>
                                <option value="service_provider">Service Provider</option>
                              </select>
                              <div v-if="errors.type" class="text-danger">{{ errors.type }}</div>
                            </p>

                            <!-- Tenant Invoice Fields -->
                            <div v-if="form.type === 'tenant'">
                              <p>
                                <strong>Tenant Name:*</strong>
                                <input
                                  type="text"
                                  v-model="searchQuery"
                                  @input="filterTenants"
                                  placeholder="Search tenant..."
                                  class="form-control"
                                />
                                <select v-model="form.tenant_id" class="form-control" size="5">
                                  <option v-for="tenant in filteredTenants" :key="tenant.id" :value="tenant.id">
                                    {{ tenant.name }}
                                  </option>
                                </select>
                                <div v-if="errors.tenant_id" class="text-danger">{{ errors.tenant_id }}</div>
                              </p>

                              <div v-if="selectedTenant">
                                <p><strong>Selected Tenant:</strong></p>
                                <p>{{ selectedTenant.name }} - {{ selectedTenant.phone }}</p>
                              </div>

                              <p>
                                <strong>Rent Month:*</strong>
                                <select v-model="form.rent_month" class="form-control">
                                  <option v-for="month in months" :key="month" :value="month">{{ month }}</option>
                                </select>
                                <div v-if="errors.rent_month" class="text-danger">{{ errors.rent_month }}</div>
                              </p>

                              <p>
                                <strong>Due Date:</strong> 
                                <input
                                  type="date"
                                  v-model="form.due_date"
                                  class="form-control"
                                  :placeholder="defaultTenantDueDate"
                                />
                                <small class="text-muted">
                                  Optional — defaults to {{ defaultTenantDueDate }} (5 days from creation)
                                </small>
                                <div v-if="errors.due_date" class="text-danger">{{ errors.due_date }}</div>
                              </p>

                            </div>

                            <!-- Service Provider Invoice Fields -->
                            <div v-if="form.type === 'service_provider'">
                              <p>
                                <strong>Service Provider:*</strong>
                                <input
                                  type="text"
                                  v-model="searchProviderQuery"
                                  @input="filterProviders"
                                  placeholder="Search provider..."
                                  class="form-control"
                                />
                                <select v-model="form.provider_id" class="form-control" size="5">
                                  <option v-for="provider in filteredProviders" :key="provider.id" :value="provider.id">
                                    {{ provider.name }}
                                  </option>
                                </select>
                                <div v-if="errors.provider_id" class="text-danger">{{ errors.provider_id }}</div>
                              </p>

                              <p>
                                <strong>Services:*</strong>
                              </p>
                              <div class="mb-2">
                                <span
                                  v-for="(service, index) in providerServices"
                                  :key="service"
                                  @click="toggleService(service)"
                                  :class="['badge', form.services.includes(service) ? 'bg-success' : 'bg-secondary', 'me-1', 'mb-1']"
                                  style="cursor: pointer;"
                                >
                                  {{ service }}
                                </span>
                              </div>
                              <small class="text-muted d-block mb-2">
                                Hold <kbd>Ctrl</kbd> (or <kbd>Cmd</kbd> on Mac) and click to select multiple services
                              </small>
                              <div v-if="errors.services" class="text-danger">{{ errors.services }}</div>



                              <p>
                                <strong>Due Date:*</strong>
                                <input type="date" v-model="form.due_date" class="form-control" />
                                <div v-if="errors.due_date" class="text-danger">{{ errors.due_date }}</div>
                              </p>
                            </div>

                            <!-- Amount Due -->
                            <p v-if="form.type === 'service_provider'">
                              <strong>Amount Due:*</strong>
                              <input type="number" v-model="form.amount_due" class="form-control" placeholder="Enter amount" />
                              <div v-if="errors.amount_due" class="text-danger">{{ errors.amount_due }}</div>
                            </p>

                          </div>

                          <!-- Modal Footer -->
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal" @click="closeModal">Close</button>
                            <button type="button" @click="createAddInvoice" class="btn btn-primary" style="background-color: forestgreen; border-color: darkgreen;">
                              Save and add another
                            </button>
                            <button type="button" @click="createInvoice" class="btn btn-primary" style="background-color: darkgreen; border-color: darkgreen;">
                              Save
                            </button>
                          </div>

                        </div>
                      </div>
                    </div>


                    <!-- Edit Invoice Modal -->
                    <div
                      class="modal fade"
                      id="EditInvoiceModal"
                      tabindex="-1"
                      aria-labelledby="EditInvoiceModalLabel"
                      aria-hidden="true"
                    >
                      <div class="modal-dialog">
                        <div class="modal-content">

                          <!-- Header -->
                          <div class="modal-header">
                            <h5 class="modal-title" id="EditInvoiceModalLabel">Edit Invoice</h5>
                            <button
                              type="button"
                              class="btn-close"
                              data-bs-dismiss="modal"
                              aria-label="Close"
                            ></button>
                          </div>

                          <!-- Body -->
                          <div class="modal-body">
                            <!-- Invoice Number -->
                            <p class="fw-bold">#{{ selectedStatement.invoice_number }}</p>

                            <!-- Tenant or Service Provider Name -->
                            <p v-if="selectedStatement?.type === 'tenant'">
                              <strong>Tenant:</strong> {{ selectedStatement?.tenant?.name || 'N/A' }}
                            </p>

                            <p v-else-if="selectedStatement?.type === 'service_provider'">
                              <strong>Service Provider:</strong> {{ selectedStatement?.provider?.name || 'N/A' }}
                            </p>

                            <!-- Amount -->
                            <div v-if="selectedStatement" class="row mt-3">
                              <div class="col-sm-12">
                                <label v-if="selectedStatement?.type === 'tenant'" class="form-label fw-semibold">
                                  Amount Due (Exclusive of Monthly Bills)
                                </label>
                                <label v-else-if="selectedStatement?.type === 'service_provider'" class="form-label fw-semibold">
                                  Amount Due
                                </label>
                                <input
                                  type="text"
                                  name="total"
                                  v-model="form.amount_due"
                                  class="form-control"
                                />
                              </div>
                            </div>
                          </div>

                          <!-- Footer -->
                          <div class="modal-footer">
                            <button
                              type="button"
                              class="btn btn-secondary"
                              data-bs-dismiss="modal"
                            >
                              Close
                            </button>
                            <button
                              type="button"
                              class="btn btn-primary"
                              style="background-color: darkgreen; border-color: darkgreen;"
                              @click.prevent="confirmEditInvoice"
                            >
                              Save changes
                            </button>
                          </div>

                        </div>
                      </div>
                    </div>

                 
    
                  </div>
                </div><!-- End Top Selling -->
    
            </div>
        </section>
    </Master>
    </template>
    
    <script>
    import Master from "@/components/Master.vue";
    import axios from "axios";
    import Swal from 'sweetalert2';
    import "jquery/dist/jquery.min.js";
    import "datatables.net-dt/js/dataTables.dataTables";
    import "datatables.net-dt/css/jquery.dataTables.min.css";
    import $ from "jquery";
    import moment from 'moment';
    import jsPDF from 'jspdf';
    import * as XLSX from 'xlsx';
    import aprilLogo from '@/assets/img/apex-logo.png';

    // Add CSRF token to Axios headers
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    const toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000
    });
    
    window.toast = toast;
    
    export default {
      data(){
        return {
          awaitinginvoicing: [],
          tenants: [],
          serviceproviders: [],
          services: [],
          months: [],
          collectedTotal: 0,
          expensesTotal: 0,
          user: [],
          rentMonth: '',
          selectedStatement: {
            tenant: {
              phone: ''
            }
          },          
          refNo: '',
          rentMonth: '',
          propertyName: '',
          unitName: '',
          tenantName: '',
          phoneNumber: '',
          details: '',
          currentMonth: '',
          logoBase64: '',

          form: {
            type: 'tenant',
            tenant_id: null,
            provider_id: null,
            services: [],
            rent_month: null,
            due_date: null,
            amount_due: null,
            phone: ''
          },
          errors: {
            water_bill: '',
            phone: '',
            tenant: '',
            rentmonth: ''
          },
          searchQuery: '',
          filteredTenants: [],
          selectedTenant: null,
          searchProviderQuery: '',
          filteredProviders: [],
          providerServices: [], // dynamically loaded skills
          services: [],
          loading: true,
          invoicing: false,
          generating: false,
          initializing: true,
          addInvoicePermission: '',
          invoiceTenantPermission: '',
          lastmonthBalance: '',
          lastMonth: '',
          prevArrears: ''
        }
      },
      watch: {
        'form.provider_id'(newVal) {
          this.selectedProvider = this.filteredProviders.find(p => p.id === newVal);
          if (this.selectedProvider) {
            // Parse the skills JSON into an array
            this.providerServices = JSON.parse(this.selectedProvider.skills || '[]');
          } else {
            this.providerServices = [];
          }
          // Reset selected service if provider changes
          this.form.service_id = null;
        }
      },
      methods: {
        async autoGenerateInvoices() {
          // Show confirmation using SweetAlert2
          const result = await Swal.fire({
            title: 'Are you sure?',
            text: 'This will auto-generate invoices for all tenants for this month.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, generate invoices!',
            cancelButtonText: 'Cancel'
          });

          if (!result.isConfirmed) {
            return; // User cancelled
          }

          this.loading = true;

          try {
            const response = await axios.post('/api/invoices/auto-generate');
            toast.fire('Success!', 'Invoices auto-generated successfully!', 'success');

            // Reload your lists to reflect new invoices
            await this.loadLists();
          } catch (error) {
            console.error(error);
            Swal.fire('Error!', error.response?.data?.message || 'An error occurred while auto-generating invoices', 'error');
          } finally {
            this.loading = false;
          }
        },

        isOverdue(date) {
          if (!date) return false;
          const due = new Date(date);
          const today = new Date();

          // Compare only date part, ignore time
          due.setHours(0,0,0,0);
          today.setHours(0,0,0,0);

          return due < today;
        },
        toggleService(service) {
          const index = this.form.services.indexOf(service);
          if (index === -1) {
            this.form.services.push(service); // add if not selected
          } else {
            this.form.services.splice(index, 1); // remove if already selected
          }
        },
        viewInvoice(invoice)
        {
          this.selectedStatement = invoice;
          this.refNo = invoice.invoice_number;
          this.rentMonth = invoice.rent_month;
          this.propertyName = invoice.property ? invoice.property.name : 'N/A';
          this.unitName = invoice.unit ? invoice.unit.name : 'N/A';
          this.tenantName = invoice.tenant ? invoice.tenant.name : 'N/A';
          this.phoneNumber = invoice.tenant ? invoice.tenant.phone : 'N/A';
          this.emailCount = this.selectedStatement.email_count;
          this.whatsappCount = this.selectedStatement.whatsapp_count;
          this.smsCount = this.selectedStatement.sms_count;
          console.log("pussy", invoice)
          if (!invoice.tenant.phone) {
            invoice.tenant.phone = ''
          }
          // Show the modal after fetching data
          const modal = new bootstrap.Modal(document.getElementById('viewInvoiceModal'));
          modal.show();
        },
        navigateTo(location){
            this.$router.push(location)
        },
        async createInvoice() {
          this.errors = {}; // reset errors

          if (this.form.type === 'tenant') {
            // Tenant validation
            if (!this.form.tenant_id) {
              this.errors.tenant_id = 'Tenant name is required.';
            }
            if (!this.form.rent_month) {
              this.errors.rent_month = 'Rent month is required.';
            }
          } else if (this.form.type === 'service_provider') {
            // Service provider validation
            if (!this.form.provider_id) {
              this.errors.provider_id = 'Service provider is required.';
            }
            if (!this.form.services || !this.form.services.length) {
              this.errors.services = 'Please select at least one service.';
            }
            if (!this.form.amount_due) {
              this.errors.amount_due = 'Amount due is required.';
            }
            if (!this.form.due_date) {
              this.errors.due_date = 'Due date is required.';
            }
          }

          // Stop if there are any errors
          if (Object.keys(this.errors).length) {
            return;
          }

          try {
            const response = await axios.post("/api/invoices", this.form);
            console.log("Invoice created", response);
            toast.fire('Success!', 'Invoice created!', 'success');
          } catch (error) {
            console.log(error);
            Swal.fire('Error!', error.response?.data?.message || 'An error occurred', 'error');
          } finally {
            this.loading = false;

            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addInvoiceModal'));
            modal.hide();

            // Reset form
            this.form = {
              type: 'tenant',
              tenant_id: null,
              provider_id: null,
              services: [],
              rent_month: null,
              due_date: null,
              amount_due: null,
            };

            this.selectedTenant = null;
            this.searchQuery = '';
            this.searchProviderQuery = '';
            await this.loadLists();
          }
        },
        
        async createAddInvoice() {
          this.errors = {}; // reset errors

          // Validate based on invoice type
          if (this.form.type === 'tenant') {
            if (!this.form.tenant_id) {
              this.errors.tenant_id = 'Tenant name is required.';
            }
            if (!this.form.rent_month) {
              this.errors.rent_month = 'Rent month is required.';
            }
          } else if (this.form.type === 'service_provider') {
            if (!this.form.provider_id) {
              this.errors.provider_id = 'Service provider is required.';
            }
            if (!this.form.services || !this.form.services.length) {
              this.errors.services = 'Please select at least one service.';
            }
            if (!this.form.amount_due) {
              this.errors.amount_due = 'Amount due is required.';
            }
            if (!this.form.due_date) {
              this.errors.due_date = 'Due date is required.';
            }
          }

          // Stop if there are any errors
          if (Object.keys(this.errors).length) {
            return;
          }

          try {
            const response = await axios.post("/api/invoices", this.form);
            console.log("Invoice created", response);
            toast.fire('Success!', 'Invoice created!', 'success');
          } catch (error) {
            console.log(error);
            Swal.fire('Error!', error.response?.data?.message || 'An error occurred', 'error');
          } finally {
            this.loading = false;

            // Keep modal open for adding another invoice

            // Reset form fields for next entry
            this.form = {
              type: this.form.type, // keep current type
              tenant_id: null,
              provider_id: null,
              services: [],
              rent_month: null,
              due_date: null,
              amount_due: null,
            };

            this.selectedTenant = null;
            this.searchQuery = '';
            this.searchProviderQuery = '';

            // Reload the list so new invoice shows immediately
            await this.loadLists();
          }
        },

        editInvoice(statement)
        {
          this.selectedStatement = statement;
          this.form.tenant_id = this.selectedStatement.tenant_id;
          this.form.balance = this.selectedStatement.balance;
          this.form.total = this.selectedStatement.total;
          this.tenant = this.selectedStatement.name;
          this.form.amount_due = this.selectedStatement.amount_due;

          console.log("omba", statement)
          // Show the modal after fetching data
            const modal = new bootstrap.Modal(document.getElementById('EditInvoiceModal'));
            modal.show();
        },
        async confirmEditInvoice() {
          if (this.selectedStatement && this.selectedStatement.id) {
            // Implement logic to edit the invoice
            console.log("Editing invoice with statement ID:", this.selectedStatement.id);
            
            await this.saveEditInvoice();

            // Close the modal after editing the invoice
            const modal = bootstrap.Modal.getInstance(document.getElementById('EditInvoiceModal'));
            modal.hide();

            // Ensure fresh data is loaded
            await this.loadLists();
          }
        },

        saveEditInvoice() {
            return new Promise((resolve, reject) => {
                let payload; // Define payload variable outside the if-else blocks

                  payload = {
                      tenant_id: this.form.tenant_id,
                      status: 'unpaid',
                      amount_due: this.form.amount_due
                  };
               

                axios.put("/api/invoices/" + this.selectedStatement.id, payload)
                    .then(response => {
                        console.log(response);
                         toast.fire(
                              'Success!',
                              'Invoice updated!',
                              'success'
                          );

                        resolve(); // Resolve the promise when settleTenant completes successfully
                    })
                    .catch(error => {
                        console.log(error);
                        reject(error); // Reject the promise if there's an error
                    });
            });
        },
        deleteInvoice(id){
          Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#006400',
            cancelButtonColor: '#FFA500',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) { 
            //send request to the server
            axios.delete('/api/invoices/'+id).then(() => {
            toast.fire(
              'Deleted!',
              'Invoice has been deleted.',
              'success'
            )

            this.loadLists();
            }).catch(() => {
              Swal.fire(
              'Failed!',
              'There was something wrong.',
              'warning'
            )
            }); 
            }else if(result.isDenied) {
              console.log('cancelled')
            }
                             
          })
        },
        generateMonthsArray() {
          const date = new Date();
          const currentYear = date.getFullYear();
          const monthsArray = [];

          const monthNames = [
            "January", "February", "March", "April", "May", "June",
            "July", "August", "September", "October", "November", "December"
          ];

          for (let i = 0; i < 12; i++) {
            monthsArray.push(`${monthNames[i]} ${currentYear}`); //revert back to current year on new year
          }

          return monthsArray;
        },
         openModal() {
          // $('#addInvoiceModal').modal('show'); // Show the modal using jQuery
          const modal = new bootstrap.Modal(document.getElementById('addInvoiceModal'));
          modal.show();
        },
        closeModal() {
          this.form = {
            type: 'tenant',
            tenant_id: null,
            provider_id: null,
            services: [],      // for service selection
            rent_month: null,
            due_date: null,
            amount_due: null,
          };
          this.errors = {};
          this.selectedTenant = null;
          this.searchQuery = '';
          this.filteredTenants = [];
          this.searchProviderQuery = '';
          this.filteredProviders = [];

          const modalEl = document.getElementById('addInvoiceModal');
          const modal = bootstrap.Modal.getInstance(modalEl);
          if (modal) modal.hide();
        },

        async generateInvoices() {
          this.generating = true; // Set loading to true
          try {
            const response = await axios.post('/api/generate-monthly-statements');
            toast.fire(
              'Success!',
              response.data.message,
              'success'
            );
            this.loadLists();
          } catch (error) {
            console.error('Error generating statement:', error);
            // alert('Failed to generate monthly statement.');
          } finally {
            this.generating = false; // Set loading to false
          }
        },

        invoiceTenant(statement) {
          this.selectedStatement = statement;
          this.form.water_bill = ''; // Reset the form field
          this.errors.water_bill = ''; // Reset the error message
          const modal = new bootstrap.Modal(document.getElementById('invoiceTenantModal'));
          modal.show();
        },
        confirmInvoiceTenant() {
          if (!this.selectedStatement || !this.selectedStatement.id) {
            console.log("No statement selected.");
            return;
          }

          if (!this.selectedStatement.tenant?.phone) {
            this.errors.phone = 'Phone number is required';
            return;
          }

          this.invoicing = true;
          this.errors = {};
          this.successMessage = '';

          const payload = {
            statement_id: this.selectedStatement.id,
            phone: this.selectedStatement.tenant.phone,
            amount: this.selectedStatement.amount_due,
            account_reference: this.selectedStatement.invoice_number,
            description: `Rent payment for ${this.selectedStatement.rent_month}`
          };

          console.log('Sending STK Push:', payload);

          axios.post('/api/mpesa/stk-push', payload)
            .then(response => {
              Swal.fire(
                'STK Sent!',
                'Please complete payment on your phone.',
                'success'
              );
            })
            .catch(error => {
              console.error(error);

              toast.fire(
                'Error!',
                error.response?.data?.message || 'Failed to send STK Push.',
                'error'
              );
            })
            .finally(() => {
              this.invoicing = false;

              const modal = bootstrap.Modal.getInstance(
                document.getElementById('invoiceTenantModal')
              );
              modal.hide();

              this.loadLists();
            });
        },
                
        settleTenant(id, tenantId){
            // this.$router.push('/settlestatement/'+id)
            this.$router.push({ 
              name: 'settlestatement', // Assuming you have named routes
              params: { 
                id: id,
                tenantId: tenantId
              } 
            });

        },
        capitalizeFirstLetter(str) {
          return str.charAt(0).toUpperCase() + str.slice(1);
        },
        formatNumber(value) {
            // Check if the value is not a number
            if (isNaN(value)) {
                return value; // Return as it is
            }
            
            // Convert the value to a string
            let stringValue = value.toString();

            // Split the string into integer and decimal parts
            let parts = stringValue.split('.');

            // Format the integer part with commas
            parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');

            // If there's a decimal part, limit it to 2 decimal places
            if (parts.length > 1) {
                parts[1] = parts[1].substring(0, 2);
            } else {
                parts.push('00'); // If no decimal part exists, append '00'
            }

            // Join the parts back together with a decimal point
            return parts.join('.');
        },

        format_date(value){
          if(value){
            return moment(String(value)).format('DD/MM/YYYY')
          }
        },
        capitalizeFirstLetter(str) {
          return str.charAt(0).toUpperCase() + str.slice(1);
        },
        exportToExcel() {
          const invoicesData = this.awaitinginvoicing.map(statement => ({
            "PROPERTY": statement.property ? statement.property.name : 'N/A',
            "H/S NO": statement.unit ? statement.unit.unit_number : 'N/A',
            "TENANT": statement.tenant ? statement.tenant.first_name + ' ' + statement.tenant.last_name : 'N/A',
            "DUE": this.formatNumber(statement.total),
            "RENT": statement.unit ? this.formatNumber(statement.unit.monthly_rent) : 'N/A',
            "GARBAGE": statement.unit ? this.formatNumber(statement.unit.garbage_fee) : 'N/A',
            "WATER": this.formatNumber(statement.water_bill ?? "N/A"),
            "PAID": this.formatNumber(statement.paid),
            "BALANCE": this.formatNumber(statement.balance),
            "GENERATED ON": this.format_date(statement.created_at ?? "N/A"),
          }));

          const worksheet = XLSX.utils.json_to_sheet(invoicesData);
          const workbook = XLSX.utils.book_new();
          XLSX.utils.book_append_sheet(workbook, worksheet, "AWAITING INVOICING");

          // Customize the filename with a timestamp
          const timestamp = new Date().toISOString().slice(0, 19).replace(/-/g, "").replace(/:/g, "").replace(/T/g, "_");
          const filename = `AWAITING_INVOICING_${timestamp}.xlsx`;
          
          XLSX.writeFile(workbook, filename);
        },
        generatePDF() {
            let pdfName = 'Full Statement';
            var doc = new jsPDF('landscape');
            const maxRowsPerPage = 13; // Adjust this value based on the number of rows you want per page

            // Add top-left header
            const rightHeaderText = 'April Properties\nKakamega-Webuye Rd, ACK Building\nTel: 0720 020 401\nP. O. Box 2973-50100, Kakamega\nEmail: propertapril@gmail.com';
            const rightHeaderFontSize = 12;
            const rightheaderX = 20; // Adjust the X coordinate
            const rightheaderY = 10;

            doc.setFontSize(rightHeaderFontSize);
            doc.setTextColor(44, 62, 80);
            doc.text(rightHeaderText, rightheaderX, rightheaderY, { align: 'left' });

            // Add top-right header
            const headerText = 'Generated on: ' + new Date().toLocaleString();
            const headerFontSize = 12;
            const headerX = doc.internal.pageSize.width - 20; // Adjust the X coordinate
            const headerY = 10;

            doc.setFontSize(headerFontSize);
            doc.setTextColor(44, 62, 80);
            doc.text(headerText, headerX, headerY, { align: 'right' });

            // Add image at the top
            const imageUrl = '/images/apex-logo.png'; // Replace with the URL of your image
            const imageWidth = 50; // Adjust the width of the image as needed
            const imageHeight = 50; // Adjust the height of the image as needed
            const imageX = (doc.internal.pageSize.width - imageWidth) / 2;
            const imageY = 20;
            doc.addImage(imageUrl, 'JPEG', imageX, imageY, imageWidth, imageHeight);

           // Add title
            const titleText = (' Full Rent Statement').toUpperCase();
            const titleFontSize = 18;
            const titleWidth = doc.getStringUnitWidth(titleText) * titleFontSize / doc.internal.scaleFactor;
            const titleX = (doc.internal.pageSize.width - titleWidth) / 2;
            const titleY = imageY + imageHeight + 10;

            doc.setFontSize(titleFontSize);
            doc.setTextColor(44, 62, 80); // Set text color to a dark shade
            doc.text(titleText, titleX, titleY);



            // // Add subtitle with date information
            // doc.setFontSize(14);
            // doc.setTextColor(52, 73, 94); // Set text color to a slightly lighter shade
            // doc.text('Generated on: ' + new Date().toLocaleString(), 20, imageY + imageHeight + 20);

            const netRentTotal = this.totalPaid - this.totalAmountPaid;          

            doc.setFontSize(14);
            doc.setTextColor(52, 73, 94); // Set text color to a slightly lighter shade

            let textY = imageY + imageHeight + 20; // Initial y-coordinate for the first text

            doc.text('Total Rent Collected: '  + 'KES ' + this.formatNumber(this.totalPaid), 20, textY);
            textY += 10; // Increment y-coordinate for the next text

            doc.text('Total Expenses Incurred: '  + 'KES ' + this.formatNumber(this.totalAmountPaid), 20, textY);
            textY += 10; // Increment y-coordinate for the next text

            doc.text('Total Rent Less Expenses: ' + 'KES ' + this.formatNumber(netRentTotal) , 20, textY);
            textY += 10; // Increment y-coordinate for the next text
            

            doc.setFontSize(12);
            doc.setTextColor(0);

            let headerYPos = imageY + imageHeight + 45;
            let cellHeight = 10;
            let cellPadding = 2;
            let lineHeight = 5;
            let columnWidths = [60, 30, 70, 30, 30, 30];
            let columnHeaders = ['Invoiced On', 'Status', 'Detail', 'Total', 'Paid', 'Bal'];

            let xPos = 20;
            doc.setDrawColor(0);

            for (let i = 0; i < columnWidths.length; i++) {
                doc.rect(xPos, headerYPos, columnWidths[i], cellHeight);
                doc.setTextColor(0); // Set text color to black
                doc.text(columnHeaders[i], xPos + cellPadding, headerYPos + cellHeight - cellPadding);
                xPos += columnWidths[i];
            }


            let currentPage = 1;
            let currentRow = 0;

            this.awaitinginvoicing.forEach((statement, index) => {
                if (currentRow >= maxRowsPerPage) {
                    doc.addPage();
                    headerYPos = 20;
                    currentRow = 0;
                    currentPage++;
                    xPos = 20;
                    for (let i = 0; i < columnWidths.length; i++) {
                        doc.rect(xPos, headerYPos, columnWidths[i], cellHeight, 'F');
                        doc.setTextColor(0); // Set text color to black
                        doc.text(columnHeaders[i], xPos + cellPadding, headerYPos + cellHeight - cellPadding);
                        xPos += columnWidths[i];
                    }
                    headerYPos += cellHeight;
                }

                let yPos = headerYPos + (currentRow + 1) * lineHeight;
                xPos = 20;
                for (let i = 0; i < columnWidths.length; i++) {
                    doc.rect(xPos, yPos, columnWidths[i], cellHeight);
                    switch (i) {
                        case 0:
                            doc.text(this.format_date(statement.updated_at), xPos + cellPadding, yPos + cellHeight - cellPadding);
                            break;
                        case 1:
                        let statusText;

                        if (statement.status === 1) {
                            statusText = 'Settled';
                        } else if (statement.status === 0) {
                            statusText = 'Not Settled';
                        } else {
                            statusText = 'Vacant';
                        }
                            doc.text(statusText, xPos + cellPadding, yPos + cellHeight - cellPadding);
                            break;
                        case 2:
                            doc.text(statement.details, xPos + cellPadding, yPos + cellHeight - cellPadding);
                            break;
                        case 3:
                            doc.text(this.formatNumber(statement.total), xPos + cellPadding, yPos + cellHeight - cellPadding);
                            break;
                        case 4:
                            doc.text(this.formatNumber(statement.paid), xPos + cellPadding, yPos + cellHeight - cellPadding);
                            break;
                        case 5:
                            doc.text(this.formatNumber(statement.balance), xPos + cellPadding, yPos + cellHeight - cellPadding);
                            break;
                    }
                    xPos += columnWidths[i];
                }
                currentRow++;

            });
            

            // Add subtitle with date information
            
            // Add footer
            doc.setFontSize(10);
            doc.text('Generated on: ' + new Date().toLocaleString(), 20, doc.internal.pageSize.height - 10);



            // Call the function to add expenses to the PDF with pagination
            let totalPages = this.addExpensesToPDF(this.expenses, doc);
            // Save the PDF
            // let fileName = 'Full Statement' + '_Page_' + currentPage + '.pdf';
            let fileName = 'Full Statement' + '_Total_Pages_' + totalPages + '.pdf';

            doc.save(fileName);
        },
        // Function to add expenses to the PDF with pagination
        addExpensesToPDF(expenses, doc) {
            // Add content headers for expenses
            doc.addPage(); // Add a new page for Expenses
            doc.setFontSize(14);
            doc.setTextColor(44, 62, 80);
            doc.text('Expenses', 20, 20);

            doc.setFontSize(12);
            doc.setTextColor(0);

            // Draw table headers and borders dynamically based on the HTML structure
            let expenseHeaderYPos = 30;
            let expenseCellHeight = 10;
            let expenseCellPadding = 2;
            let expenseLineHeight = 5;
            let expenseColumnWidths = [60, 40, 60, 30, 60];

            // Define column headers for Expenses
            let expenseColumnHeaders = ['Type', 'Amount(KES)', 'Expended To', 'Checked By', 'Checked On'];

            // Draw headers with borders dynamically based on calculated column widths
            let expenseXPos = 20;
            doc.setDrawColor(0);
            doc.setFillColor(255, 255, 255); // Set header background color to white

            for (let i = 0; i < expenseColumnWidths.length; i++) {
                doc.setFillColor(255, 255, 255); // Set fill color to white
                doc.rect(expenseXPos, expenseHeaderYPos, expenseColumnWidths[i], expenseCellHeight, 'F');
                doc.setTextColor(0); // Set text color to black
                doc.text(expenseColumnHeaders[i], expenseXPos + expenseCellPadding, expenseHeaderYPos + expenseCellHeight - expenseCellPadding);
                expenseXPos += expenseColumnWidths[i];
            }


            let currentPage = 1;
            let currentRow = 0;
            const maxRowsPerPage = 28; // Adjust this value based on the number of rows you want per page

            // Iterate through expenses and add them to the PDF with dynamic borders
            expenses.forEach((expense, index) => {
                if (currentRow >= maxRowsPerPage) {
                    doc.addPage(); // Add a new page if the maximum rows per page is exceeded
                    expenseHeaderYPos = 20;
                    currentRow = 0;
                    currentPage++;
                    expenseXPos = 20;
                    // Draw headers for expenses on new page
                    for (let i = 0; i < expenseColumnWidths.length; i++) {
                        doc.rect(expenseXPos, expenseHeaderYPos, expenseColumnWidths[i], expenseCellHeight, 'F');
                        doc.setTextColor(0); // Set text color to black
                        doc.text(expenseColumnHeaders[i], expenseXPos + expenseCellPadding, expenseHeaderYPos + expenseCellHeight - expenseCellPadding);
                        expenseXPos += expenseColumnWidths[i];
                    }
                    expenseHeaderYPos += expenseCellHeight;
                }

                let yPos = expenseHeaderYPos + (currentRow + 1) * expenseLineHeight;
                expenseXPos = 20;
                // Add expense data
                for (let i = 0; i < expenseColumnWidths.length; i++) {
                    doc.rect(expenseXPos, yPos, expenseColumnWidths[i], expenseCellHeight);
                    switch (i) {
                        case 0:
                            doc.text(this.capitalizeFirstLetter(expense.payment_type), expenseXPos + expenseCellPadding, yPos + expenseCellHeight - expenseCellPadding);
                            break;
                        case 1:
                            doc.text(this.formatNumber(expense.amount_paid), expenseXPos + expenseCellPadding, yPos + expenseCellHeight - expenseCellPadding);
                            break;
                        case 2:
                            doc.text(expense.paid_to, expenseXPos + expenseCellPadding, yPos + expenseCellHeight - expenseCellPadding);
                            break;
                        case 3:
                            doc.text(`${expense.user.first_name} ${expense.user.last_name}`, expenseXPos + expenseCellPadding, yPos + expenseCellHeight - expenseCellPadding);
                            break;
                        case 4:
                            doc.text(this.format_date(expense.created_at), expenseXPos + expenseCellPadding, yPos + expenseCellHeight - expenseCellPadding);
                            break;
                    }
                    expenseXPos += expenseColumnWidths[i];
                }
                currentRow++;
            });
  
            doc.setFontSize(10);
            doc.text('Generated on: ' + new Date().toLocaleString(), 20, doc.internal.pageSize.height - 10);

            return currentPage; // Return the total number of pages used for expenses
        },
        async loginUwazii() {
          try {
            const data = JSON.stringify({
              "username": "April_Properties",
              "password": "Mosobo*123#"
            });

            const config = {
              method: 'post',
              maxBodyLength: Infinity,
              url: 'https://restapi.uwaziimobile.com/v1/authorize',
              headers: {
                'Content-Type': 'application/json'
              },
              data: data
            };

            const response = await axios(config);
            this.authorizationCode = response.data.data.authorization_code; // Save authorizationCode into this.authorizationCode
            console.log(this.authorizationCode);
          } catch (error) {
            console.log(error);
          }
        },
        async getAccessToken() {
          try {
            const data = JSON.stringify({
              "authorization_code": this.authorizationCode
            });

            const config = {
              method: 'post',
              maxBodyLength: Infinity,
              url: 'https://restapi.uwaziimobile.com/v1/accesstoken',
              headers: {
                'Content-Type': 'application/json'
              },
              data: data
            };

            const response = await axios(config);
            this.accessToken = response.data.data.access_token; // Save accessToken into this.accessToken
            console.log(this.accessToken);
          } catch (error) {
            console.log(error);
          }
        },
        loadLogo() {
          fetch(aprilLogo)
            .then(response => response.blob())
            .then(blob => {
              const reader = new FileReader();
              reader.readAsDataURL(blob);
              reader.onloadend = () => {
                this.logoBase64 = reader.result;
                // console.log(this.logoBase64)
              };
            })
            .catch(error => {
              console.error('Error converting image to base64:', error);
            });
        },
        buildInvoiceContent() {
          // Determine whether to include the row
          const logoBase64 = this.logoBase64 || ''; // Fallback if no logo is provided
          const watermarkText = 'INVOICE';
          // Build the HTML content for the receipt
          const receiptHTML = `
            <!DOCTYPE html>
            <html lang="en">
            <head>
              <meta charset="UTF-8">
              <meta name="viewport" content="width=device-width, initial-scale=1.0">
              <title>Invoice Of Payment - ${this.refNo}</title>
              <style>
                body {
                  font-family: Arial, sans-serif;
                  margin: 0;
                  padding: 0;
                  background-color: #f5f5f5;
                }
                .receipt {
                  max-width: 600px;
                  margin: 20px auto;
                  padding: 20px;
                  background-color: #fff;
                  border: 2px solid #ccc;
                  display: flex;
                  flex-direction: column;
                }
                 .watermark {
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%) rotate(-45deg);
                    font-size: 80px;
                    color: rgba(0, 0, 0, 0.1); /* Adjust the transparency as needed */
                    white-space: nowrap;
                    z-index: 0;
                    pointer-events: none; /* Prevents watermark from interfering with other elements */
                  }
                .receipt-header {
                  display: flex;
                  justify-content: space-between;
                  align-items: center;
                  margin-bottom: 50px;
                }
                .company-info {
                  text-align: left;
                }
                .company-info img {
                  max-width: 150px;
                  height: auto;
                }
                .receipt-info {
                  margin-bottom: 50px;
                }
                .receipt-info p {
                  margin: 5px 0;
                  color: #555;
                }
                 .additional-info {
                  margin-bottom: 30px;
                  font-size: 16px;
                  color: #333333;
                }
                .additional-info p {
                  margin: 8px 0;
                }
                .payment-info {
                  margin-bottom: 30px;
                  font-size: 16px;
                  color: #333333;
                  text-align: center;
                }
                .payment-info p {
                  margin: 8px 0;
                }
                .receipt-table {
                  width: 100%;
                  border-collapse: collapse;
                  margin-bottom: 50px;
                }
                .receipt-table th, .receipt-table td {
                  padding: 8px;
                  border-bottom: 1px solid #ccc;
                }
                .receipt-table th {
                  text-align: left;
                  background-color: #f2f2f2;
                  color: #333;
                }
                .receipt-table td {
                  text-align: left;
                  color: #666;
                }
                .receipt-footer {
                  text-align: center;
                  margin-top: auto;
                }
                .receipt-footer p {
                  margin: 5px 0;
                  color: #777;
                }
              </style>
            </head>
            <body>
            <div class="watermark">${watermarkText}</div>
              <div class="receipt">
                <div class="receipt-header">
                  <div class="company-logo">
                    <img src="${logoBase64}" alt="Company Logo" style="max-width: 150px; height: auto;">
                  </div>
                  <div class="company-info">
                    <p>Kakamega-Webuye Rd, ACK Building</p>
                    <p>Phone: (0720) 020-401 </p>
                    <p> Email: propertapril@gmail.com</p>
                    <p> Website: www.aprilproperties.co.ke</p>
                  </div>
                </div>
                <div class="receipt-info">
                  <p><strong>#${this.refNo}</strong></p>
                  <p><strong>Invoice Date:</strong> ${this.format_date(this.invoicedAt ?? 'N/A')}</p>
                  <p><strong>Due Date:</strong>  ${this.dueDate ?? 'N/A'}</p>
                  
                </div>
                <div class="additional-info">
                    <p><strong>Invoiced To</strong></p>
                    <p><strong></strong> ${this.invoicedTenantFullName}</p>
                    <p><strong></strong> ${this.name ?? 'Victoria Apartments'} - ${this.unitName}</p>
                    <p><strong></strong> ${this.rentMonth}</p>
                </div>
                <table class="receipt-table">
                  <thead>
                    <tr>
                      <th>Description</th>
                      <th>Amount</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td>Total Rent Due ${this.water}</td>
                      <td>KES ${this.formatNumber(this.dueAmount)}</td>
                    </tr>
                  </tbody>
                  <tfoot>
                    <tr>
                      <th>Total Amount Due:</th>
                      <td>KES ${this.formatNumber(this.dueAmount)}</td>
                    </tr>
                  </tfoot>
                </table>
                <div class="payment-info">
                  <p><strong>Payment Options:</strong></p>
                  <p>Mobile Money: Paybill - ${this.paybillNo ?? 'N/A'} Account Number - ${this.accountNo ?? 'N/A'}</p>
                </div>
                <div class="receipt-footer">
                  <p>Printed on ${this.format_date(new Date().toLocaleString())}</p>
                </div>
              </div>
            </body>
            </html>
          `;

          return receiptHTML;
        },
        async sendMail(statement)
        {
          console.log("motto", statement)
          this.dueAmount = statement.total;
          this.dueWater = statement.water_bill;
          this.tenantId = statement.tenant_id;
          this.rentMonth = statement.rent_month;
          this.details = statement.details;
          this.refNo = statement.ref_no;
          this.createdAt = statement.created_at;
          this.invoicedAt = statement.updated_at;
          this.propertyId = statement.pms_property_id;
          this.unitId = statement.pms_unit_id;
          this.waterBillAmount = statement.water_bill;
          if(this.waterBillAmount == 0)
           {
              this.water = '';
           }
           else
           {
              this.water = '(Incl. Water Bill)';
           }
          this.getUnit(this.unitId);

          // Fetch tenant data and wait for it to complete
          await this.getTenant(this.tenantId);

          this.mailing = true;
          this.invoicing = false;

          // Check if tenantEmail is provided
            if (!this.invoicedTenantMail) {
              this.mailing = false;
              this.invoicing = false;
              // Close the modal after invoicing
                const modal = bootstrap.Modal.getInstance(document.getElementById('invoiceTenantModal'));
                modal.hide();
                
                Swal.fire({
                    title: 'Error sending email',
                    text: 'Please ensure ' + this.invoicedTenantFullName + ' has a valid email address',
                    icon: 'warning',
                });
                return;
            }

          // Calculate the due date (5th of the rent month)
          this.dueDate = this.calculateDueDate(this.rentMonth);

          //fetch payment details
          if(this.propertyId == 5)
          {
            await this.getUnitInfo(this.unitId);              
          }
          else
          {
            await this.getProperty(this.propertyId);              
          }

          // First, generate the invoice content and create a Blob
            const invoiceContent = this.buildInvoiceContent();
            const blob = new Blob([invoiceContent], { type: 'text/html' });
            const file = new File([blob], 'invoice.html', { type: 'text/html' });

          // Prepare form data to send the email request to the backend
            const formData = new FormData();
            formData.append('name', this.invoicedTenantFullName);
            // formData.append('email', this.invoicedTenantMail);
            formData.append('email', 'mmosobo@gmail.com'); //for testing
            formData.append('due_water', this.dueWater);
            formData.append('due_amount', this.dueAmount);
            formData.append('account_no', this.accountNo);
            formData.append('paybill_no', this.paybillNo);
            formData.append('subject', this.rentMonth + ' Invoice Payment Reminder');
            formData.append('message', 'Dear ' + this.invoicedTenantFullName +', this is a kind reminder that your invoice no. '+ this.refNo +' which was generated on '+ this.format_date(this.createdAt) +' is due on '+ this.dueDate + '. To service this invoice, pay via M-Pesa paybill number: '+ this.paybillNo + ' account number: ' + this.accountNo + ' amount: '+ this.dueAmount );
            formData.append('invoice', file);

            try {
                // Make API call to send email
                await axios.post('/api/send-tenantinvoice', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                });
                this.mailing = false;                
                // Close the modal after invoicing
                const modal = bootstrap.Modal.getInstance(document.getElementById('invoiceTenantModal'));
                modal.hide();

                toast.fire(
                    'To: ' + this.invoicedTenantMail,
                    'Email has been sent successfully.',
                    'success'
                );
            } catch (error) {
                Swal.fire({
                    title: 'Error sending email',
                    text: error.response?.data?.message || error.message,
                    icon: 'warning',
                });
            }
        },

        async sendSms(statement) {
              await this.loginUwazii();
              await this.getAccessToken();
              console.log("uhunye", statement);
              const dueAmount = statement.total;
              const dueWater = statement.water_bill;
              const tenantId = statement.tenant_id;

              // Fetch tenant data and wait for it to complete
              await this.getTenant(tenantId);

              // Ensure tenant details are available before creating the payload
              const payload = {
                  'token': this.accessToken,
                  'tenantName': this.invoicedTenantName,
                  'dueWater': dueWater,  // Use the dueWater from statement
                  'dueAmount': dueAmount,  // Use the dueAmount from statement
                  'number': this.formatPhoneNumber(this.invoicedTenantPhone.toString()) // Format the phone number
              };

              axios.post('/api/sendsms', payload)
                  .then((response) => {
                      console.log("sms status", payload);
                  })
                  .catch((error) => {
                      console.error("Error sending sms:", error);
                  });
          },

        async getTenant(id) {
              try {
                  const response = await axios.get('/api/pmstenant/' + id);
                  this.tenant = response.data.tenant;
                  console.log("omollo", this.tenant);
                  this.invoicedTenantName = this.tenant.first_name;
                  this.invoicedTenantLName = this.tenant.last_name;
                  this.invoicedTenantFullName = this.invoicedTenantName + " " + this.invoicedTenantLName;
                  this.invoicedTenantPhone = this.tenant.phone_number;
                  this.invoicedTenantMail = this.tenant.email_address;
              } catch (error) {
                  console.log('error', error);
              }
        },
        async getProperty() {
            try {
                const response = await axios.get('/api/pmsproperty/' + this.propertyId);
                this.property = response.data.property;
                this.name = response.data.property.name;
                this.accountNo = response.data.property.account_number;
                this.paybillNo = response.data.property.paybill_number;
                // console.log("property", response);
            } catch (error) {
                console.error("Error fetching property data:", error);
            }
        },

        async getUnitInfo() {
            try {
                const response = await axios.get(`/api/pmsunit/${this.unitId}`);
                this.unit = response.data.unit;
                this.accountNo = response.data.unit.account_number;
                this.paybillNo = response.data.unit.paybill_number;
                // console.log("aprilthings", response);
            } catch (error) {
                console.error("Error fetching unit data:", error);
            }
        },
        getUnit(unitNumber) {
            axios.get('/api/pmsunit/' + parseInt(unitNumber))
                .then((response) => {
                  this.unit = response.data.unit;
                  this.unitName = this.unit.unit_number;
                  this.unitRent = this.unit.monthly_rent;
                  this.unitSecurityFee = this.unit.security_fee;
                  this.unitGarbageFee = this.unit.garbage_fee;
                  this.unitType = this.unit.type;
                    console.log("unit", this.unit);
                    // Further processing of the response data if needed
                })
                .catch((error) => {
                    console.error("Error fetching unit:", error);
                });
        },

        formatPhoneNumber(number) {
            // Ensure number is a string
            number = number.toString();
            
            // Clean the phone number and ensure it has the 254 prefix
            if (number.startsWith('0')) {
                // Remove the leading zero and add the 254 prefix
                number = '254' + number.substring(1);
            } else if (!number.startsWith('254')) {
                // If the number is not already prefixed with 254, add it
                number = '254' + number;
            }
            return number;
        },
        getCurrentMonth() {
          const currentDate = new Date();
          const months = [
            'January', 'February', 'March', 'April', 'May', 'June', 
            'July', 'August', 'September', 'October', 'November', 'December'
          ];
          const monthIndex = currentDate.getMonth();
          const year = currentDate.getFullYear();
          return `${months[monthIndex]} ${year}`;
        },
        loadLists(id) {
          this.initializing = true; // Start spinner
          axios.get('api/provider-invoices/'+ id)
            .then((response) => {
              this.awaitinginvoicing = response.data.providerinvoices;

            //   this.services = response.data.lists.services;
            //   this.tenants = response.data.lists.tenants;
            //   this.filteredTenants = this.tenants;
            //   this.serviceproviders = response.data.lists.serviceproviders;
            //   this.filteredProviders = this.serviceproviders;              
            //   this.expenses = response.data.lists.pmsexpenses;
              // Calculate the total amount paid
              this.totalAmountPaid = this.calculateTotalAmountPaid();
              setTimeout(() => {
                $("#AllStatementsTable").DataTable();
              }, 10);
            })
            .catch((error) => {
              console.error('Error fetching user list:', error);
            })
            .finally(() => {
              this.initializing = false; // Stop spinner
            });
        },
        calculateDueDate(rentMonth) {
          let dueDate = new Date(rentMonth);
          dueDate.setDate(5);

          const day = String(dueDate.getDate()).padStart(2, '0');
          const month = String(dueDate.getMonth() + 1).padStart(2, '0');
          const year = dueDate.getFullYear();

          return `${day}/${month}/${year}`;
        },        
         filterTenants() {
          const query = this.searchQuery.toLowerCase();
          this.filteredTenants = this.tenants.filter(tenant => {
            const fullName = `${tenant.name}`.toLowerCase();
            return fullName.includes(query);
          });
        },
         filterProviders() {
          const query = this.searchProviderQuery.toLowerCase();
          this.filteredProviders = this.serviceproviders.filter(provider => {
            const fullName = `${provider.name}`.toLowerCase();
            return fullName.includes(query);
          });
        },        
        calculateTotalAmountPaid() {
        if (!this.expenses || this.expenses.length === 0) {
              return 0; // If expenses data is empty or undefined, return 0
            }

            // Use reduce to sum up the amount_paid property for all expenses
            return this.expenses.reduce((total, expense) => total + expense.amount_paid, 0);
        },
        calculateTotal(property) {
          // Function to calculate total for Total, Paid, and Bal columns

          return this.awaitinginvoicing.reduce((total, statement) => total + (statement[property] || 0), 0);
        },
      },
      components : {
        Master,
      },
      computed:
      {
        defaultTenantDueDate() {
          const d = new Date(Date.now() + 5 * 24 * 60 * 60 * 1000);
          const day = String(d.getDate()).padStart(2, '0');
          const month = String(d.getMonth() + 1).padStart(2, '0'); // months are 0-indexed
          const year = d.getFullYear();
          return `${day}-${month}-${year}`; // DD-MM-YYYY
        },

        // Computed property to calculate total due
        totalDue() {
          return this.calculateTotal('total');
        },
        // Computed property to calculate total paid
        totalPaid() {
          return this.calculateTotal('paid');
        },
        // Computed property to calculate total balance
        totalBalance() {
          return this.calculateTotal('balance');
        },
        selectedTenant() {
          // Find the selected tenant object based on selected ID
          return this.tenants.find(tenant => tenant.id === this.form.tenant_id);
        }
      },      
      mounted(){
        this.months = this.generateMonthsArray();
        this.user = localStorage.getItem('user');
        this.user = JSON.parse(this.user);
        this.userId = this.user.id;
        this.loadLists(this.userId);
        this.currentMonth = this.getCurrentMonth();
        this.loadLogo();
        this.currentUser = JSON.parse(localStorage.getItem('user')) || {};
        this.current_user_id = this.currentUser.id;
        this.current_user = this.currentUser.first_name + " " + this.currentUser.last_name;
        this.form.phone = this.currentUser.phone;

      }
    }
    </script>
    
    
    