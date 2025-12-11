<template>
    <Master>
        <section class="section dashboard">
          <div class="row">
    
                <!-- Top Selling -->
                <div class="col-12">
                  <div class="card top-selling overflow-auto">
    
                    <div class="filter">
                    <!--                       <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                          <h6>Filter</h6>
                        </li>
    
                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                      </ul> -->
                    </div>
    
                    <div class="card-body pb-0">
                      <h5 class="card-title">Pending Tickets <span>| Open support tickets of M-Keja</span></h5>
                      <p class="card-text">
                        <div class="row">
                          <div class="col d-flex">
                   
                   
                            <!-- <router-link v-if="addLandlordPermission" to="/add-pmslandlord" custom v-slot="{ href, navigate, isActive }"> -->
                                <a
                                  :href="href"
                                  :class="{ active: isActive }"
                                  class="btn btn-sm btn-primary rounded-pill"
                                  style="background-color: darkgreen; border-color: darkgreen;"
                                  @click="addTicket()"
                                >
                                  Add Ticket
                                </a>
                            <!-- </router-link> -->
                          </div>
                          <div class="col-auto d-flex justify-content-end">
                          <div class="btn-group" role="group">
                              <button id="btnGroupDrop1" type="button" style="background-color: darkgreen; border-color: darkgreen;" class="btn btn-sm btn-primary rounded-pill dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-add-line"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                     <a @click="navigateTo('/clients' )" class="dropdown-item" href="#"><i class="ri-user-fill mr-2"></i>Clients</a>
                                    <a @click="navigateTo('/savings' )" class="dropdown-item" href="#"><i class="ri-user-fill mr-2"></i>Savings</a>
                                    <a @click="navigateTo('/loans' )" class="dropdown-item" href="#"><i class="ri-user-fill mr-2"></i>Loans</a>
                                </div>
                              </div>
                            </div>
                        </div>   
            
                      </p>
    
                      <table id="PendingTicketsTable" class="table table-borderless">
                        <thead>
                          <tr>
                            <th scope="col">Full Name</th>
                            <th scope="col">Priority</th>
                            <th scope="col">Description</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <!-- Spinner shown while data is initializing -->
                        <tbody v-if="initializing">
                          <tr>
                            <td colspan="7" class="text-center">
                              <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                        <tbody v-else>
                          <tr v-for="ticket in closedtickets" :key="ticket.id">
                            <td>{{ticket.name}}</td>
                            <td>{{ticket.priority ?? "N/A"}}</td>
                            <td>{{ticket.description ?? "N/A"}}</td>
                            <td>
                              <!-- ACTIVE -->
                              <span v-if="ticket.status == 'open'" class="badge bg-success">
                                <i class="bi bi-check-circle me-1"></i> Open
                              </span>

                              <!-- PENDING -->
                              <span v-else-if="ticket.status == 'in progress'" class="badge bg-warning text-dark">
                                <i class="bi bi-hourglass-split me-1"></i> In Progress
                              </span>

                              <!-- SUSPENDED -->
                              <!-- <span v-else-if="ticket.status == 'suspended'" class="badge bg-danger">
                                <i class="bi bi-slash-circle me-1"></i> Suspended
                              </span> -->
                            </td>
                           
                            <td>
                              <div class="btn-group" role="group">
                                  <button id="btnGroupDrop1" type="button" style="background-color: darkgreen; border-color: darkgreen;" class="btn btn-sm btn-primary rounded-pill dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Action
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                  <a @click="viewTicket(ticket)" class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a> 
                                  <a @click="editTicket(ticket)" class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                  <a @click="deleteTicket(ticket.id)" class="dropdown-item" href="#"><i class="ri-delete-bin-line mr-2"></i>Delete</a>
                                  </div>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
    
                    </div>
    
                  </div>
                </div><!-- End Top Selling -->

              <!-- View Ticket Modal -->
              <div class="modal fade" id="viewTicketModal" tabindex="-1" aria-labelledby="viewTicketModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                  <div class="modal-content">

                    <div class="modal-header">
                      <h5 class="modal-title">View Ticket Details</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body" v-if="selectedTicket">

                      <!-- Profile Photo -->
                      <div class="text-center mb-3" v-if="selectedTicket">
                        <img 
                          :src="selectedTicket.profile_photo
                                  ? `/storage/${selectedLandlord.profile_photo}` 
                                  : (selectedLandlord.profile_photo_url 
                                      ? selectedLandlord.profile_photo_url 
                                      : defaultProfile)"
                          @error="($event.target.src = defaultProfile)" 
                          alt="Profile Photo" 
                          class="rounded-circle border" 
                          style="height: 120px; width: 120px; object-fit: cover; object-position: center;"
                        />
                      </div>


                      <div class="row g-3">

                        <!-- BASIC INFO -->
                        <div class="col-md-6" v-if="selectedTicket.name">
                          <strong>Full Name:</strong> <br> {{ selectedTicket.user.name }}
                        </div>

                        <div class="col-md-6" v-if="selectedTicket.priority">
                          <strong>Priority:</strong> <br> {{ selectedTicket.priority }}
                        </div>

                        <div class="col-md-6" v-if="selectedTicket.description">
                          <strong>Description:</strong> <br> {{ selectedTicket.description }}
                        </div>

                        <!-- STATUS -->
                        <div class="col-md-6" v-if="selectedTicket.status">
                          <strong>Status:</strong> <br> {{ selectedTicket.status }}
                        </div>

                      </div>
                    </div>

                    <div class="modal-footer">
                      <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>

                  </div>
                </div>
              </div>


                <!-- Add Ticket Modal -->
                <div class="modal fade" id="AddTicketModal" tabindex="-1" aria-labelledby="AddTicketModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                      <div class="modal-header">
                        <h5 class="modal-title" id="AddTicketModalLabel">Add Ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>

                      <div class="modal-body">
                        <form class="row g-3 needs-validation" novalidate>

                          <!-- Hidden ID -->
                          <input type="hidden" v-model="data.id" />

                          <!-- Priority -->
                          <div class="col-md-6">
                            <label class="form-label">Priority*</label>
                            <select name="role" v-model="data.priority" class="form-select" id="userrole">
                                <option value="" selected disabled>Select priority</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>

                          </div>

                          <!-- Password -->
                          <div class="col-md-6">
                            <label class="form-label">Password*</label>
                            <div class="input-group">
                              <input id="password" :type="showPassword ? 'text' : 'password'" class="form-control" v-model="data.password" required>
                              <span class="input-group-text" style="cursor:pointer" @click="showPassword = !showPassword">
                                <i :class="showPassword ? 'fa fa-eye' : 'fa fa-eye-slash'"></i>
                              </span>
                            </div>
                          </div>

                          <!-- Phone -->
                          <div class="col-md-6">
                            <label class="form-label">Phone</label>
                            <input type="text" id="phone" class="form-control" v-model="data.phone">
                          </div>

                          <!-- Address Fields -->
                          <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" v-model="data.address">
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" v-model="data.city">
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">County</label>
                            <input type="text" class="form-control" v-model="data.county">
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">Postal Code</label>
                            <input type="text" class="form-control" v-model="data.postal_code">
                          </div>

                          <!-- DOB + Gender -->
                          <div class="col-md-6">
                            <label class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" v-model="data.dob">
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select class="form-select" v-model="data.category">
                              <option value="">Select</option>
                              <option value="login">Login issues</option>
                              <option value="data_retrieval">Data retrieval</option>
                              <option value="other">Other</option>
                            </select>
                          </div>

                          <!-- Additional Fields -->
                          <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <textarea type="text" class="form-control" v-model="data.description" />
                          </div>

                          <!-- Status -->
                          <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" v-model="data.status">
                              <option value="active">Active</option>
                              <option value="pending">Pending</option>
                              <option value="suspended">Suspended</option>
                            </select>
                          </div>

                          <!-- Profile Photo: allow File or URL -->
                          <div class="col-md-12">
                            <label class="form-label">Profile Photo</label>

                            <div class="mb-2">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="photoFileOption" value="file" v-model="photoMode">
                                <label class="form-check-label" for="photoFileOption">Upload file</label>
                              </div>
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" id="photoUrlOption" value="url" v-model="photoMode">
                                <label class="form-check-label" for="photoUrlOption">Use image URL</label>
                              </div>
                            </div>

                            <!-- File input -->
                            <div v-if="photoMode === 'file'">
                              <input type="file" class="form-control" accept="image/*" @change="handlePhotoUpload">
                            </div>

                            <!-- URL input -->
                            <div v-if="photoMode === 'url'">
                              <input type="url" class="form-control" v-model="data.profile_photo_url" placeholder="https://example.com/photo.jpg" @input="updatePreviewFromUrl">
                            </div>

                            <!-- Preview -->
                            <div v-if="data.profile_photo_preview" class="mt-2">
                              <img :src="data.profile_photo_preview" class="img-thumbnail" style="max-height: 130px;">
                              <div class="small text-muted mt-1">Preview</div>
                            </div>

                            <!-- Optionally show validation message -->
                            <div v-if="errors.profile_photo" class="text-danger small mt-1">{{ errors.profile_photo }}</div>
                          </div>


                        </form>
                      </div>

                      <!-- Footer -->
                      <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-success" @click="submit" style="background: darkgreen; border-color: darkgreen;">
                          Save
                        </button>
                      </div>

                    </div>
                  </div>
                </div>


                <!-- EDIT Ticket MODAL -->
                <div class="modal fade" id="EditTicketModal" tabindex="-1" aria-labelledby="EditTicketModalLabel" aria-hidden="true">
                  <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                      <div class="modal-header">
                        <h5 class="modal-title">Edit Ticket</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                      </div>

                      <div class="modal-body">
                        <form class="row g-3">

                          <!-- Hidden ID -->
                          <input type="hidden" v-model="form.id" />

                          <div class="col-md-6">
                            <label class="form-label">Description</label>
                            <textarea type="text" class="form-control" v-model="form.description" />
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <select class="form-select" v-model="form.priority">
                              <option value="">Select</option>
                              <option value="high">High</option>
                              <option value="medium">Medium</option>
                              <option value="low">Low</option>
                            </select>
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select class="form-select" v-model="form.category">
                              <option value="">Select</option>
                              <option value="login">Login issues</option>
                              <option value="data_retrieval">Data retrieval</option>
                              <option value="other">Other</option>
                            </select>
                          </div>

                          <!-- Status -->
                          <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" v-model="form.status">
                              <option value="open">Open</option>
                              <option value="in progress">In Progress</option>
                              <option value="resolved">Resolved</option>
                              <option value="closed">Closed</option>
                            </select>
                          </div>

                          <!-- Profile Photo (file or URL) -->
                          <div class="col-md-12">
                            <label class="form-label">Profile Photo</label>

                            <div class="mb-2">
                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="file" v-model="photoMode">
                                <label class="form-check-label">Upload file</label>
                              </div>

                              <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" value="url" v-model="photoMode">
                                <label class="form-check-label">Use URL</label>
                              </div>
                            </div>

                            <div v-if="photoMode === 'file'">
                              <input type="file" class="form-control" @change="handleEditPhotoUpload">
                            </div>

                            <div v-if="photoMode === 'url'">
                              <input type="url" class="form-control" v-model="form.profile_photo_url" @input="updateEditPreviewFromUrl">
                            </div>

                            <div v-if="form.profile_photo_preview" class="mt-2">
                              <img :src="form.profile_photo_preview" class="img-thumbnail" style="max-height: 130px;">
                            </div>

                          </div>

                        </form>
                      </div>

                      <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-success" @click="submitChanges" style="background: darkgreen; border-color: darkgreen;">
                          Save Changes
                        </button>
                      </div>

                    </div>
                  </div>
                </div>

                    

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
   import DefaultProfile from '@/assets/img/default-profile.png'
    import $ from "jquery";
    
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
          closedtickets: [],
          user: [],
          selectedTicket: {},
          showPassword: false,
          defaultProfile: DefaultProfile,
          photoMode: 'file', // 'file' or 'url' — default to file
          errors: {},
          form: {
            id: "",
            category: "",
            priority: "",
            description: "",
            status: "open",

            profile_photo_file: null,
            profile_photo_preview: null,
            profile_photo_url: '' // for URL input
          },
          data: {
            id: "",
            category: "",
            priority: "",
            description: "",
            status: "open",

            profile_photo_file: null,
            profile_photo_preview: null,
            profile_photo_url: '' // for URL input
          },
          initializing: true

        }
      },
      watch: {
        // When mode changes, clear the other input and reset preview
        photoMode(newMode) {
          if (newMode === 'file') {
            this.data.profile_photo_url = '';
            this.data.profile_photo_preview = this.data.profile_photo_file 
              ? this.data.profile_photo_preview 
              : '';
          } else if (newMode === 'url') {
            this.data.profile_photo_file = null;
            this.data.profile_photo_preview = this.data.profile_photo_url || '';
          }
        }
      },      
      methods: {
        handlePhotoUpload(event) {
          const file = event.target.files[0];
          if (!file) return;

          // Validate type & size
          if (!file.type.startsWith('image/')) {
            this.errors.profile_photo = 'Selected file is not an image';
            return;
          }
          if (file.size > 5 * 1024 * 1024) { // 5MB limit
            this.errors.profile_photo = 'Image must be <= 5 MB';
            return;
          }

          this.errors.profile_photo = null;
          this.data.profile_photo_file = file;

          // Preview
          const reader = new FileReader();
          reader.onload = e => this.data.profile_photo_preview = e.target.result;
          reader.readAsDataURL(file);
          },
          handleEditPhotoUpload(event) {
            const file = event.target.files[0];
            if (file) {
              this.form.profile_photo_file = file;
              this.form.profile_photo_preview = URL.createObjectURL(file);
            }
          },

        updatePreviewFromUrl() {
          const url = this.data.profile_photo_url?.trim();
          if (!url) {
            this.data.profile_photo_preview = '';
            return;
          }

          // Optional: simple extension check
          const lower = url.toLowerCase();
          if (!lower.match(/\.(jpeg|jpg|png|gif|svg|webp)(\?.*)?$/)) {
            // Could warn user if desired
          }

          this.data.profile_photo_preview = url;
          this.errors.profile_photo = null;
        }, 
        updateEditPreviewFromUrl() {
          this.form.profile_photo_preview = this.form.profile_photo_url;
        },
          
        viewTicket(ticket)
        {
          console.log(this.selectedTicket)
          this.selectedTicket = ticket;
          // Show the modal after fetching data
          const modal = new bootstrap.Modal(document.getElementById('viewTicketModal'));
          modal.show();
        },
        editTicket(ticket)
        {
          this.form = {
            id: ticket.id,
            category: ticket.category ?? "",
            priority: ticket.priority ?? "",
            description: ticket.description ?? "",
            profile_photo_url: ticket.profile_photo_url || null,
            profile_photo_preview: ticket.profile_photo 
                ? `/storage/${ticket.profile_photo}`
                : ticket.profile_photo_url
          }; 
           // Set correct mode
          this.photoMode = ticket.profile_photo ? "file" : "url";         
          // Show the modal after fetching data
          const modal = new bootstrap.Modal(document.getElementById('EditTicketModal'));
          modal.show();
        },
        validateFormChanges() {
          let isValid = true;

          if (!this.form.description) {
            isValid = false;
            document.getElementById('description_edit').classList.add('is-invalid');
          } else {
            document.getElementById('description_edit').classList.remove('is-invalid');
          }

          if (!this.form.category) {
            isValid = false;
            document.getElementById('category_edit').classList.add('is-invalid');
          } else {
            document.getElementById('category_edit').classList.remove('is-invalid');
          }

          return isValid;
        },
          
        async submitChanges() {
            if (this.validateFormChanges()) {        
                // Start submitting process
                this.submitting = true;
                
                try {
                    // Simulate asynchronous submission process (you would replace this with your actual submission logic)
                    await this.submitFormChanges();

                    // Submission successful
                    this.submitted = true;
                } catch (error) {
                    // Handle submission error
                    console.error("Submission error:", error);
                } finally {
                    // End submitting process
                    this.submitting = false;
                }
            }
        },
        async submitFormChanges() {
          try {
            let formData = new FormData();

            // Append normal fields
            const fields = [
              "category", "priority", "description"
            ];

            fields.forEach(field => {
              if (this.form[field] !== undefined) {
                formData.append(field, this.form[field]);
              }
            });

            // Handle Skills (array)
            if (Array.isArray(this.form.skills)) {
              formData.append("skills", JSON.stringify(this.form.skills));
            }

            // Handle Photo Upload
            if (this.photoMode === "file" && this.form.profile_photo_file) {
              formData.append("profile_photo", this.form.profile_photo_file);
            }

            // Handle Photo URL
            if (this.photoMode === "url" && this.form.profile_photo_url) {
              formData.append("profile_photo_url", this.form.profile_photo_url);
            }

            const response = await axios.post(
              `/api/support-tickets/${this.form.id}?_method=PUT`,
              formData,
              { headers: { "Content-Type": "multipart/form-data" } }
            );

            // Success
            toast.fire('Success!', 'Ticket details updated!', 'success');

            const modal = bootstrap.Modal.getInstance(
              document.getElementById('EditTicketModal')
            );
            modal.hide();

            this.loadLists();

          } catch (error) {
            console.error(error);
            toast.fire(
              'Error!',
              error.response?.data?.message || 'Something went wrong.',
              'error'
            );
          }
        },

        addTicket()
        {
          // Show the modal after fetching data
          const modal = new bootstrap.Modal(document.getElementById('AddTicketModal'));
          modal.show();
        },
        async submit() {
            if (this.validateForm()) {

                // Start submitting process
                this.submitting = true;
                
                try {
                    // Simulate asynchronous submission process (you would replace this with your actual submission logic)
                    await this.submitForm();

                    // Submission successful
                    this.submitted = true;
                } catch (error) {
                    // Handle submission error
                    console.error("Submission error:", error);
                } finally {
                    // End submitting process
                    this.submitting = false;
                }
            }
        },
        validateForm() {
          let isValid = true;

          const fields = [
            { id: 'category', value: this.data.category },
            { id: 'description',  value: this.data.description },
            { id: 'priority',      value: this.data.priority },
          ];

          fields.forEach(field => {
            const el = document.getElementById(field.id);

            if (!field.value || field.value === "") {
              el.classList.add('is-invalid');
              isValid = false;
            } else {
              el.classList.remove('is-invalid');
            }
          });

          return isValid;
        },
       
        async submitForm() {
          try {
            // Prepare FormData for file upload + other fields
            const formData = new FormData();

            // Append all fields
            for (const key in this.data) {
              if (key === 'profile_photo_file' && this.data.profile_photo_file) {
                // append the actual file
                formData.append('profile_photo', this.data.profile_photo_file);
              } else if (key !== 'profile_photo_file') {
                formData.append(key, this.data[key]);
              }
            }

            // Send POST request as multipart/form-data
            const response = await axios.post("api/support-tickets", formData, {
              headers: {
                'Content-Type': 'multipart/form-data'
              }
            });

            console.log(response);

            toast.fire(
              'Success!',
              'Ticket added!',
              'success'
            );

            // Close the modal after submit
            const modal = bootstrap.Modal.getInstance(document.getElementById('AddTicketModal'));
            modal.hide();

            // Reset form properly (avoid assigning '')
            this.data = {
              id: "",
              category: "",
              description: "",
              status: "open",
              profile_photo_file: null,
              profile_photo_preview: null,
              profile_photo_url: ''
            };

            this.loadLists();

          } catch (error) {
            console.log(error);
            toast.fire(
              'Error!',
              error.response?.data?.message || 'An error occurred while adding the user.',
              'error'
            );
          }
        },

        getPhoto(user) {
            // user can be an object containing profile_photo and profile_photo_url
            if (user.profile_photo && user.profile_photo !== '') {
                // file stored in local storage
                return `/storage/${user.profile_photo}`;
            } else if (user.profile_photo_url && user.profile_photo_url !== '') {
                // external URL
                return user.profile_photo_url;
            } else {
                // fallback placeholder
                return '/images/default-profile.png';
            }
        },


        navigateTo(location){
            this.$router.push(location)
        },
        deleteTicket(id){
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
                  axios.delete('/api/support-tickets/'+id).then(() => {
                  toast.fire(
                    'Deleted!',
                    'Ticket has been deleted.',
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
        loadLists() {
          this.initializing = true; // Start spinner
          axios.get('api/lists')
            .then((response) => {
              this.closedtickets = response.data.lists.closedtickets;
              console.log(response)

              setTimeout(() => {
                $("#PendingTicketsTable").DataTable();
              }, 10);
            })
            .catch((error) => {
              console.error('Error fetching user list:', error);
            })
            .finally(() => {
              this.initializing = false; // Stop spinner
            });
        },
      },
      components : {
          Master,
      },
      mounted(){
        this.loadLists();
        // this.user = localStorage.getItem('user');
        // this.user = JSON.parse(this.user);
        // this.userId = this.user.id;
        // this.currentUser = JSON.parse(localStorage.getItem('user')) || {};
        // this.current_user_id = this.currentUser.id;
        // this.current_user = this.currentUser.first_name + " " + this.currentUser.last_name;

      }
    }
    </script>
    
    
    