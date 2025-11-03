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
                      <h5 class="card-title">All Users <span>| System Users of M-Keja</span></h5>
                      <p class="card-text">
                        <div class="row">
                          <div class="col d-flex">
                   
                   
                            <!-- <router-link v-if="addLandlordPermission" to="/add-pmslandlord" custom v-slot="{ href, navigate, isActive }"> -->
                                <a
                                  :href="href"
                                  :class="{ active: isActive }"
                                  class="btn btn-sm btn-primary rounded-pill"
                                  style="background-color: darkgreen; border-color: darkgreen;"
                                  @click="addUser()"
                                >
                                  Add User
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
    
                      <table id="AllUsersTable" class="table table-borderless">
                        <thead>
                          <tr>
                            <th scope="col">Full Name</th>
                            <th scope="col">Email Address</th>
                            <th scope="col">Role</th>
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
                          <tr v-for="user in users" :key="user.id">
                            <td>{{user.name}}</td>
                            <td>{{user.email ?? "N/A"}}</td>
                            <td>{{user.role ?? "N/A"}}</td>
                            <td>
                              <div class="btn-group" role="group">
                                  <button id="btnGroupDrop1" type="button" style="background-color: darkgreen; border-color: darkgreen;" class="btn btn-sm btn-primary rounded-pill dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Action
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                  <a @click="viewUser(user)" class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a> 
                                  <a @click="editUser(user)" class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                  <a @click="deleteUser(user.id)" class="dropdown-item" href="#"><i class="ri-delete-bin-line mr-2"></i>Delete</a>
                                  </div>
                              </div>
                            </td>
                          </tr>
                        </tbody>
                      </table>
    
                    </div>
    
                  </div>
                </div><!-- End Top Selling -->

                <!-- Modal -->
                <div class="modal fade" id="viewUserModal" tabindex="-1" aria-labelledby="viewUserModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="viewUserModalLabel">View User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p v-if="selectedUser">
                              <strong>Full Name:</strong> {{ selectedUser.name }} 
                            </p>
                            <p v-if="selectedUser.email">
                              <strong>Email Address:</strong> {{ selectedUser.email }}
                            </p>
                            <p v-else>
                              
                            </p>
                            <p v-if="selectedUser.role">
                              <strong>Role:</strong> {{ selectedUser.role }}
                            </p>
                            <p v-else>
                              
                            </p>

                            
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                </div>

                <!--Add User Modal -->
                <div class="modal fade" id="AddUserModal" tabindex="-1" aria-labelledby="AddUserModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="AddUserModalLabel">Add User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
    
                                <div class="row m-auto p-auto justify-content- g-3 needs-validation" novalidate="" autocomplete="off">
                    
                                    <div class="row mb-3"></div>
                                    <div class="form-group row">
                                      <input
                                          type="hidden"
                                          id="user_id"
                                          name="user_id"
                                          value="1"
                                          class="form-control"
                                      />
                                        <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">First Name*</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="text"
                                                placeholder="First Name"
                                                id="first_name"
                                                name="first_name"
                                                v-model="data.first_name"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter first name!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Last Name*</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="text"
                                                placeholder="Last Name"
                                                id="last_name"
                                                name="last_name"
                                                v-model="data.last_name"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter last name!</div>
                                          </div>
                                      </div>
                    
                                    </div>
                                    <div class="row mb-3"></div>
                                    <div class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Email Address</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="text"
                                                placeholder="Email Address"
                                                id="email"
                                                name="title"
                                                v-model="data.email"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter email address!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Role</label>
                                          <div class="col-sm-12">
                                            <select name="role" v-model="data.role" class="form-select" id="role">
                                                <option value="0" disabled selected>Select Role</option>
                                                <option value="1">Administrator</option>
                                                <option value="2">Loan Officer</option>
                                                <option value="3">User</option>

                                            </select>  
                                            <div class="invalid-feedback">Please enter role!</div>
                                          </div>
                                      </div>

                                    </div>

                                    <div class="row mb-3"></div>

                                </div>


                                

                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" style="background-color: darkgreen; border-color: darkgreen;" class="btn btn-primary" @click.prevent="submit()">Save changes</button>
                              </div>
                            </div>
                          </div>
                </div>

                <!--Edit User Modal -->
                <div class="modal fade" id="EditUserModal" tabindex="-1" aria-labelledby="EditUserModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="AddUserModalLabel">Edit User</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
    
                                <div class="row m-auto p-auto justify-content- g-3 needs-validation" novalidate="" autocomplete="off">
                    
                                    <div class="row mb-3"></div>
                                    <div class="form-group row">
                                      <input
                                          type="hidden"
                                          id="user_id"
                                          name="user_id"
                                          value="1"
                                          class="form-control"
                                      />
                                        <div class="col-sm-12">
                                          <label for="inputPassword" class="form-label">Name*</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="text"
                                                placeholder="Name"
                                                id="name"
                                                name="name"
                                                v-model="form.name"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback" v-if="!form.name">Please enter name!</div>
                                          </div>
                                      </div>
                    
                                    </div>
                                    <div class="row mb-3"></div>
                                    <div class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Email Address</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="text"
                                                placeholder="Email Address"
                                                id="mail"
                                                name="title"
                                                v-model="form.email"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter email address!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Role</label>
                                          <div class="col-sm-12">
                                           <select name="role" v-model="form.role" class="form-select" id="userrole">
                                                <option value="0" disabled selected>Select Role</option>
                                                <option value="1">Administrator</option>
                                                <option value="2">Loan Officer</option>
                                                <!-- <option value="other">Other</option> -->

                                            </select>  
                                            <div class="invalid-feedback">Please enter role!</div>
                                          </div>
                                      </div>

                                    </div>

                                    <div class="row mb-3"></div>

                                </div>


                                

                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" style="background-color: darkgreen; border-color: darkgreen;" class="btn btn-primary" @click.prevent="submitChanges()">Save changes</button>
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
          users: [],
          user: [],
          selectedUser: {},
          form: {
            name: '',
            email: '',
            role: '',
          
          },
          data: {
            first_name: '',
            last_name: '',
            email: '',
            role: ''
          
          },
          initializing: true

        }
      },
      methods: {
        viewUser(user)
        {
          console.log(this.selectedUser)
          this.selectedUser = user;
          // Show the modal after fetching data
          const modal = new bootstrap.Modal(document.getElementById('viewUserModal'));
          modal.show();
        },
        editUser(user)
        {
          this.form = user;
          // Show the modal after fetching data
          const modal = new bootstrap.Modal(document.getElementById('EditUserModal'));
          modal.show();
        },
        validateFormChanges() {
          let isValid = true;
          if (!this.form.name) {
              isValid = false;
              document.getElementById('name').classList.add('is-invalid');
          } else {
              document.getElementById('name').classList.remove('is-invalid');
          }
          if (!this.form.email) {
              isValid = false;
              document.getElementById('mail').classList.add('is-invalid');
          } else {
              document.getElementById('mail').classList.remove('is-invalid');
          }
          if (!this.form.role) {
              isValid = false;
              document.getElementById('userrole').classList.add('is-invalid');
          } else {
              document.getElementById('userrole').classList.remove('is-invalid');
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
            // Update client details
            const response = await axios.put(`/api/users/${this.form.id}`, this.form);
            console.log(response);

            // Show success notification
            toast.fire(
              'Success!',
              'User details updated!',
              'success'
            );

            // Close the modal after submit
            const modal = bootstrap.Modal.getInstance(document.getElementById('EditUserModal'));
            modal.hide();
            // this.form = '';
            this.loadLists();
            
          } catch (error) {
            console.error(error);
            // Display an error notification
            toast.fire(
              'Error!',
              error.response?.data?.message || 'An error occurred while updating the user details.',
              'error'
            );
          }
        },
        addUser()
        {
          // Show the modal after fetching data
          const modal = new bootstrap.Modal(document.getElementById('AddUserModal'));
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
          if (!this.data.first_name) {
              isValid = false;
              document.getElementById('first_name').classList.add('is-invalid');
          } else {
              document.getElementById('first_name').classList.remove('is-invalid');
          }
          if (!this.data.last_name) {
              isValid = false;
              document.getElementById('last_name').classList.add('is-invalid');
          } else {
              document.getElementById('last_name').classList.remove('is-invalid');
          }
          if (!this.data.email) {
              isValid = false;
              document.getElementById('email').classList.add('is-invalid');
          } else {
              document.getElementById('email').classList.remove('is-invalid');
          }   
          if (!this.data.role) {
              isValid = false;
              document.getElementById('role').classList.add('is-invalid');
          } else {
              document.getElementById('role').classList.remove('is-invalid');
          }                 
          return isValid;
       },        
       async submitForm() {
          try {
            // Create user and wait for the response
            const response = await axios.post("api/users", this.data);
            console.log(response);

            toast.fire(
              'Success!',
              'User added!',
              'success'
            );

            // Close the modal after submit
            const modal = bootstrap.Modal.getInstance(document.getElementById('AddUserModal'));
            modal.hide();
            this.data = '';
            this.loadLists();

          } catch (error) {
            console.log(error);
            // Display error notification
            toast.fire(
              'Error!',
              error.response?.data?.message || 'An error occurred while adding the user.',
              'error'
            );
          }
        },
        getPhoto()
        {
            return "/storage/users/";
        },
        navigateTo(location){
            this.$router.push(location)
        },
        deleteUser(id){
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
                  axios.delete('/api/users/'+id).then(() => {
                  toast.fire(
                    'Deleted!',
                    'User has been deleted.',
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
          axios.get('api/users')
            .then((response) => {
              this.users = response.data;
              console.log(response)

              setTimeout(() => {
                $("#AllUsersTable").DataTable();
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
    
    
    