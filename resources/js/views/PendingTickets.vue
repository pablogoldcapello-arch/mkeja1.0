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
                          <tr v-for="ticket in pendingtickets" :key="ticket.id">
                            <td>{{ticket.user.name}}</td>
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

                        <!-- Image Gallery -->
                        <div v-if="selectedTicket.images?.length" class="mt-3">
                          <strong>Gallery Images:</strong>
                          <div class="d-flex flex-wrap mt-2">
                            <div v-for="(img, i) in selectedTicket.images" :key="i" class="me-2 mb-2">
                              <img 
                                :src="'/storage/tickets/' + img.name"
                                style="width:120px; height:100px; object-fit:cover; border-radius:4px;"
                              >
                            </div>
                          </div>
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
                            <select name="role" v-model="data.priority" class="form-select" id="priority">
                                <option value="" selected disabled>Select priority</option>
                                <option value="critical">Critical</option>
                                <option value="high">High</option>
                                <option value="medium">Medium</option>
                                <option value="low">Low</option>
                            </select>

                          </div>

                          <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select class="form-select" v-model="data.category" id="category">
                              <option value="" disabled selected>Select category</option>
                              <option value="payment">Payment</option>
                              <option value="property">Property</option>
                              <option value="service">Service</option>
                              <option value="agent">Agent</option>
                              <option value="technical">Technical</option>
                              <option value="fraud">Fraud</option>
                              <option value="other">Other</option>
                            </select>
                          </div>

                          <!-- Additional Fields -->
                          <div class="col-md-12">
                            <label class="form-label">Description</label>
                            <textarea type="text" id="description" class="form-control" v-model="data.description" />
                          </div>

                        <!-- Media Upload -->
                        <div class="col-md-6">
                            <label class="form-label">Upload Screenshots</label>
                            <input type="file" name="images[]" multiple @change="handleImages">
                        </div>                          

                        <!-- Image Previews in a NEW full-width row -->
                        <div class="col-12 mt-3" v-if="images.length > 0">
                            <label class="form-label fw-bold">Preview Screenshots</label>

                            <div class="image-preview-container">
                                <div class="preview-box" v-for="(img, index) in images" :key="index">
                                    <img :src="img.preview" class="preview-img">
                                    <button class="remove-btn" @click="removeImage(index)">×</button>
                                </div>
                            </div>
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


                <!-- Edit Ticket Modal -->
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
                            <textarea type="text" id="description_edit" class="form-control" v-model="form.description" />
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">Priority</label>
                            <select class="form-select" id="priority_edit" v-model="form.priority">
                              <option value="">Select</option>
                              <option value="critical">Critical</option>
                              <option value="high">High</option>
                              <option value="medium">Medium</option>
                              <option value="low">Low</option>
                            </select>
                          </div>

                          <div class="col-md-6">
                            <label class="form-label">Category</label>
                            <select class="form-select" id="category_edit" v-model="form.category">
                              <option value="">Select category</option>
                              <option value="payment">Payment</option>
                              <option value="property">Property</option>
                              <option value="service">Service</option>
                              <option value="agent">Agent</option>
                              <option value="technical">Technical</option>
                              <option value="fraud">Fraud</option>
                              <option value="other">Other</option>
                            </select>
                          </div>

                          <!-- EXISTING IMAGES -->
                          <div class="col-md-12">
                            <label class="form-label">Existing Images</label>

                            <div class="image-preview-container">
                                <div class="preview-box" v-for="(img, index) in existingImages" :key="img.id">
                                    <img :src="getPhoto(img.name)" class="preview-img">
                                    <button class="remove-btn" @click="removeExistingImage(form.id, img.id, index)">×</button>
                                </div>
                            </div>
                          </div>

                          <!-- NEW IMAGES -->
                          <div class="col-md-12">
                            <label class="form-label">Upload New Images</label>
                            <input type="file" multiple @change="handleNewImages">

                            <div class="image-preview-container mt-2">
                                <div class="preview-box" v-for="(img, index) in newImages" :key="index">
                                    <img :src="img.preview" class="preview-img">
                                    <button class="remove-btn" @click="newImages.splice(index,1)">×</button>
                                </div>
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
          pendingtickets: [],
          user: [],
          selectedTicket: {},
          showPassword: false,
          defaultProfile: DefaultProfile,
          images: [],
          existingImages: [],
          newImages: [],
          errors: {},
          form: {
            id: "",
            category: "",
            priority: "",
            description: "",
            status: "open",

          },
          data: {
            id: "",
            category: "",
            priority: "",
            description: "",
            status: "open",

          },
          initializing: true

        }
      },      
      methods: {
        handleImages(e) {
            const files = e.target.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                this.images.push({
                    file: file,                                   // ← real file!
                    preview: URL.createObjectURL(file)           // ← preview
                });
            }
        },
        removeImage(index) {
            this.images.splice(index, 1);
        },
        handleNewImages(event) {
          const files = event.target.files;
          for (let i = 0; i < files.length; i++) {
            this.newImages.push({
              file: files[i],
              preview: URL.createObjectURL(files[i])
            });
          }
        },
        async removeExistingImage(ticketId, imageId, index) {
            try {
                await axios.delete(`/api/support-tickets/${ticketId}/images/${imageId}`);
                this.form.images.splice(index, 1); // remove from array
                toast.fire('Success!', 'Image removed!', 'success');
            } catch (error) {
                console.error(error);
                toast.fire('Error!', 'Could not remove image.', 'error');
            }
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
              error.response?.data?.message || 'An error occurred while adding the ticket.',
              'error'
            );
          }
        },

        getPhoto(name) {
          return "/storage/tickets/" + name;
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
              this.pendingtickets = response.data.lists.pendingtickets;
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
    
    
    