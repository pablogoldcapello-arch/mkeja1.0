<template>
    <Master>
        <section class="section dashboard">
          <div class="row">
    
                <!-- Top Selling -->
                <div class="col-12">
                  <div class="card top-selling overflow-auto">
    
                    <!-- <div class="filter">
                      <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                      <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        <li class="dropdown-header text-start">
                          <h6>Filter</h6>
                        </li>
    
                        <li><a class="dropdown-item" href="#">Today</a></li>
                        <li><a class="dropdown-item" href="#">This Month</a></li>
                        <li><a class="dropdown-item" href="#">This Year</a></li>
                      </ul>
                    </div> -->
    
                    <div class="card-body pb-0">
                      <h5 class="card-title">All Listings <span>| Properties for advertisement</span></h5>
                      <p class="card-text">
                   
                        <a
                            :href="href"
                            :class="{ active: isActive }"
                            class="btn btn-sm btn-primary rounded-pill"
                            style="background-color: darkgreen; border-color: darkgreen;"
                            @click="addListing()"
                        >
                            Add Listing
                        </a>
            
                      </p>
    
                      <table id="AllPropertiesTable" class="table table-borderless">
                        <thead>
                          <tr>
                            <th scope="col">Preview</th>
                            <th scope="col">Title</th>
                            <th scope="col">Price(KES)</th>
                            <th scope="col">Location</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="property in listings" :key="property.id">
                            <th scope="row"><a href="#">
                                  <img 
      v-if="property.images && property.images.length > 0"
      :src="getPhoto(property.images[0].name)" 
      class="img-thumbnail" 
      width="60" 
      height="60"
    />
                            </a></th>
                            <!-- <td>{{property["images"][0]["name"]}}</td> -->
                            <td>{{property.title}}</td>
                            <td>{{(property.price).toLocaleString()}}</td>
                            <td>{{property.address}}</td>
                            <td>{{property.status}}</td>
                            <td>
                              <div class="btn-group" role="group">
                                  <button id="btnGroupDrop1" type="button" class="btn btn-sm btn-primary rounded-pill dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Action
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                  <!-- <a class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a>                                             -->
                                  <a v-if="property.created_by == user.id" @click="navigateTo('/editproperty/'+property.id )" class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>
                                  <a v-if="property.status == 0" @click="approveProperty(property.id)" class="dropdown-item" href="#"><i class="ri-check-fill mr-2"></i>Approve</a>
                                  <a v-if="property.featured == 0 && property.status == 1" @click="featureProperty(property.id)" class="dropdown-item" href="#"><i class="ri-eye-close-fill mr-2"></i>Feature</a>
                                  <a v-if="property.featured == 1 && property.status == 1" @click="unfeatureProperty(property.id)" class="dropdown-item" href="#"><i class="ri-eye-close-fill mr-2"></i>Unfeature</a>
                                  <a v-if="property.status == 1" @click="closeProperty(property.id)" class="dropdown-item" href="#"><i class="ri-eye-close-fill mr-2"></i>Close</a>
                                  <a v-if="property.status == 2" @click="reopenProperty(property.id)" class="dropdown-item" href="#"><i class="ri-refresh-fill mr-2"></i>Reopen</a>
                                  <a @click="deleteProperty(property.id)" class="dropdown-item" href="#"><i class="ri-delete-bin-line mr-2"></i>Delete</a>
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
                <!-- <div class="modal fade" id="viewListingModal" tabindex="-1" aria-labelledby="viewListingModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="viewListingModalLabel">View Listing</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <p v-if="selectedListing">
                              <strong>Title:</strong> {{ selectedListing.title }} 
                            </p>
                            <p v-if="selectedListing.type">
                              <strong>Type:</strong> {{ selectedListing.type }}
                            </p>
                            <p v-if="selectedListing.status">
                              <strong>Status:</strong> {{ selectedListing.status }}
                            </p>
                           
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                </div> -->

                <!-- Add Listing Modal -->
                <div class="modal fade" id="AddListingModal" tabindex="-1" aria-labelledby="AddListingModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="AddListingModalLabel">Add Listing</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <form class="row g-3 needs-validation" novalidate autocomplete="off">
                        
                        <!-- Basic Info -->
                        <div class="col-md-6">
                            <label class="form-label">Title*</label>
                            <input type="text" id="title" class="form-control" v-model="data.title" placeholder="Property Title" required>
                            <div class="invalid-feedback">Please enter title!</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Type*</label>
                            <select id="type" class="form-select" v-model="data.type" required>
                            <option value="" disabled selected>Select Type</option>
                            <option value="apartment">Apartment</option>
                            <option value="house">House</option>
                            <option value="bedsitter">Bedsitter</option>
                            <option value="studio">Studio</option>
                            <option value="office">Office</option>
                            <option value="land">Land</option>
                            </select>
                            <div class="invalid-feedback">Please select type!</div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Property Type*</label>
                            <select v-model="data.type" class="form-select" id="type">
                            <option value="" disabled>Select Type</option>
                            <option value="apartment">Apartment</option>
                            <option value="house">House</option>
                            <option value="bedsitter">Bedsitter</option>
                            <option value="studio">Studio</option>
                            <option value="office">Office</option>
                            <option value="land">Land</option>
                            </select>
                            <div class="invalid-feedback">Please enter type!</div>
                        </div>
                        
                        <div class="col-md-6">
                            <label class="form-label">Status*</label>
                            <select v-model="data.status" class="form-select" id="status">
                            <option value="" disabled>Select Status</option>
                            <option value="for_sale">For Sale</option>
                            <option value="for_rent">For Rent</option>
                            <option value="sold">Sold</option>
                            <option value="occupied">Occupied</option>
                            </select>
                            <div class="invalid-feedback">Please select status!</div>
                        </div>                        

                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" id="description" v-model="data.description" rows="3" placeholder="Property Description"></textarea>
                        </div>

                        <!-- Location -->
                        <div class="col-md-6">
                            <label class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" v-model="data.address" placeholder="Address">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">City</label>
                            <input type="text" class="form-control" v-model="data.city" placeholder="City">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Neighborhood</label>
                            <input type="text" class="form-control" v-model="data.neighborhood" placeholder="Neighborhood">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Coordinates</label>
                            <input type="text" class="form-control" v-model="data.coordinates" placeholder="Latitude, Longitude">
                        </div>

                        <!-- Property Details -->
                        <div class="col-md-3">
                            <label class="form-label">Bedrooms</label>
                            <input type="number" class="form-control" v-model="data.bedrooms" min="0">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Bathrooms</label>
                            <input type="number" class="form-control" v-model="data.bathrooms" min="0">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Living Rooms</label>
                            <input type="number" class="form-control" v-model="data.living_rooms" min="0">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Kitchens</label>
                            <input type="number" class="form-control" v-model="data.kitchens" min="0">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Balcony</label>
                            <select class="form-select" v-model="data.balcony">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Floor Level</label>
                            <input type="number" class="form-control" v-model="data.floor_level" min="0">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Total Area (sqm)</label>
                            <input type="number" class="form-control" v-model="data.total_area" min="0" step="0.01">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Furnished</label>
                            <select class="form-select" v-model="data.furnished">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <!-- Financials -->
                        <div class="col-md-4">
                            <label class="form-label">Price</label>
                            <input type="number" id="price" class="form-control" v-model="data.price" min="0" step="0.01">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Currency</label>
                            <input type="text" class="form-control" v-model="data.currency" placeholder="KES">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Deposit</label>
                            <input type="number" class="form-control" v-model="data.deposit" min="0" step="0.01">
                        </div>

                        <div class="col-12">
                            <label class="form-label">Payment Terms</label>
                            <input type="text" class="form-control" v-model="data.payment_terms" placeholder="e.g. Monthly, Weekly">
                        </div>

                        <!-- Amenities -->
                        <div class="col-md-3">
                            <label class="form-label">Parking</label>
                            <select class="form-select" v-model="data.parking">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Parking Spaces</label>
                            <input type="number" class="form-control" v-model="data.parking_spaces" min="0">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Security</label>
                            <select class="form-select" v-model="data.security">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Water Supply</label>
                            <select class="form-select" v-model="data.water_supply">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Electricity</label>
                            <select class="form-select" v-model="data.electricity">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Internet</label>
                            <select class="form-select" v-model="data.internet">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Swimming Pool</label>
                            <select class="form-select" v-model="data.swimming_pool">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Gym</label>
                            <select class="form-select" v-model="data.gym">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Garden</label>
                            <select class="form-select" v-model="data.garden">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Elevator</label>
                            <select class="form-select" v-model="data.elevator">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <!-- Media Upload -->
                        <div class="col-md-4">
                            <label class="form-label">Upload Multiple Images</label>
                            <input type="file" name="images[]" multiple @change="handleImages">
                        </div>

                        <!-- Image Previews in a NEW full-width row -->
                        <div class="col-12 mt-3" v-if="images.length > 0">
                            <label class="form-label fw-bold">Preview Images</label>

                            <div class="image-preview-container">
                                <div class="preview-box" v-for="(img, index) in images" :key="index">
                                    <img :src="img.preview" class="preview-img">
                                    <button class="remove-btn" @click="removeImage(index)">×</button>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Video Tour URL</label>
                            <input type="text" class="form-control" v-model="data.video_tour" placeholder="Video URL">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Floor Plan URL</label>
                            <input type="text" class="form-control" v-model="data.floor_plan" placeholder="Floor Plan URL">
                        </div>

                        <!-- Additional Attributes -->
                        <div class="col-md-3">
                            <label class="form-label">Year Built</label>
                            <input type="number" class="form-control" v-model="data.year_built" min="1900" max="2099">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Renovated</label>
                            <select class="form-select" v-model="data.renovated">
                            <option :value="true">Yes</option>
                            <option :value="false">No</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Special Features</label>
                            <input type="text" class="form-control" v-model="data.special_features" placeholder="e.g. Sea view, Fireplace">
                        </div>

                        <!-- Contact Info -->
                        <div class="col-md-6">
                            <label class="form-label">Contact Phone</label>
                            <input type="text" id="contact_phone" class="form-control" v-model="data.contact_phone" placeholder="Phone Number">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Contact Email</label>
                            <input type="email" id="contact_email" class="form-control" v-model="data.contact_email" placeholder="Email Address">
                        </div>

                        </form>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" @click.prevent="submit()">Save Listing</button>
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
          listings: [],
          user: [],
          selectedUser: {},
          form: {
            name: '',
            email: '',
            role: '',
          
          },
          images: [],
        data: {
        // Basic Info
        title: '',
        description: '',
        type: '',       // apartment, house, etc.
        status: '',     // for_sale, for_rent, etc.

        // Location
        address: '',
        city: '',
        neighborhood: '',
        coordinates: '',

        // Property Details
        bedrooms: null,
        bathrooms: null,
        living_rooms: null,
        kitchens: null,
        balcony: false,
        floor_level: null,
        total_area: null,
        furnished: false,

        // Financials
        price: null,
        currency: 'KES',
        deposit: null,
        payment_terms: '',

        // Amenities & Facilities
        parking: false,
        parking_spaces: null,
        security: false,
        water_supply: true,
        electricity: true,
        internet: false,
        swimming_pool: false,
        gym: false,
        garden: false,
        elevator: false,

        // Media
        main_image: '',
        video_tour: '',
        floor_plan: '',

        // Contact Info
        contact_phone: '',
        contact_email: '',

        // Additional Attributes
        year_built: null,
        renovated: false,
        special_features: ''
        },
          initializing: true          
        }
      },
      methods: {
getPhoto(name) {
  return "/storage/listings/" + name;
},

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

        navigateTo(location){
            this.$router.push(location)
        },
        addListing()
        {
          // Show the modal after fetching data
          const modal = new bootstrap.Modal(document.getElementById('AddListingModal'));
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

            // Basic info
            if (!this.data.title) {
                isValid = false;
                document.getElementById('title').classList.add('is-invalid');
            } else {
                document.getElementById('title').classList.remove('is-invalid');
            }

            if (!this.data.type) {
                isValid = false;
                document.getElementById('type').classList.add('is-invalid');
            } else {
                document.getElementById('type').classList.remove('is-invalid');
            }

            if (!this.data.status) {
                isValid = false;
                document.getElementById('status').classList.add('is-invalid');
            } else {
                document.getElementById('status').classList.remove('is-invalid');
            }

            // Location (optional, but you can validate address if needed)
            if (!this.data.address) {
                isValid = false;
                document.getElementById('address').classList.add('is-invalid');
            } else {
                document.getElementById('address').classList.remove('is-invalid');
            }

            // Price (optional but recommended for financial listings)
            if (this.data.price === null || this.data.price === '') {
                isValid = false;
                document.getElementById('price').classList.add('is-invalid');
            } else {
                document.getElementById('price').classList.remove('is-invalid');
            }

            // Contact email (optional, validate if provided)
            if (this.data.contact_email && !/\S+@\S+\.\S+/.test(this.data.contact_email)) {
                isValid = false;
                document.getElementById('contact_email').classList.add('is-invalid');
            } else {
                document.getElementById('contact_email').classList.remove('is-invalid');
            }

            return isValid;
        },        
        async submitForm() {
            try {
                const formData = new FormData();

                const booleanFields = [
                    'balcony', 'furnished', 'parking', 'security', 'water_supply',
                    'electricity', 'internet', 'swimming_pool', 'gym', 'garden',
                    'elevator', 'renovated'
                ];

                // Append all normal fields
                for (const key in this.data) {
                    if (this.data[key] !== null && this.data[key] !== undefined) {
                        let value = this.data[key];

                        if (booleanFields.includes(key)) {
                            value = value == true || value == 'true' ? 1 : 0;
                        }

                        formData.append(key, value);
                    }
                }

                // Append actual FILE objects
                this.images.forEach((img) => {
                    formData.append('images[]', img.file);
                });

                const response = await axios.post("api/listings", formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        Authorization: `Bearer ${localStorage.getItem('token')}`
                    }
                });

                console.log(response);

                toast.fire('Success!', 'Listing added!', 'success');

                const modal = bootstrap.Modal.getInstance(document.getElementById('AddListingModal'));
                modal.hide();

                this.data = {};
                this.images = [];

                this.loadLists();

            } catch (error) {
                console.log(error);
                toast.fire(
                    'Error!', 
                    error.response?.data?.message || 'An error occurred while adding the listing.', 
                    'error'
                );
            }
        },      
        approveProperty(id){
          axios.put('api/approveproperty/'+ id).then(() => {
            toast.fire(
              'Successful',
              'Property has been approved',
              'success'
            ); 
            this.loadLists();                    
          }).catch(() => {
              console.log('error')
          })
        },
        featureProperty(id){
          axios.put('api/featureproperty/'+ id).then(() => {
            toast.fire(
              'Successful',
              'Property has been featured',
              'success'
            ); 
            this.loadLists();                    
          }).catch(() => {
              console.log('error')
          })
        },
        unfeatureProperty(id){
          axios.put('api/unfeatureproperty/'+ id).then(() => {
            toast.fire(
              'Successful',
              'Property has been unfeatured',
              'success'
            ); 
            this.loadLists();                    
          }).catch(() => {
              console.log('error')
          })
        },
        closeProperty(id){
          axios.put('api/closeproperty/'+ id).then(() => {
            toast.fire(
              'Successful',
              'Property has been closed',
              'success'
            ); 
            this.loadLists();                    
          }).catch(() => {
              console.log('error')
          })
        },
        reopenProperty(id){
          axios.put('api/reopenproperty/'+ id).then(() => {
            toast.fire(
              'Successful',
              'Property has been reopened',
              'success'
            ); 
            this.loadLists();                    
          }).catch(() => {
              console.log('error')
          })
        },
        deleteProperty(id){
                Swal.fire({
                  title: 'Are you sure?',
                  text: "You won't be able to revert this!",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                  if (result.isConfirmed) { 
                  //send request to the server
                  axios.delete('/api/property/'+id).then(() => {
                  toast.fire(
                    'Deleted!',
                    'Property has been deleted.',
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
            axios.get('api/lists').then((response) => {
            this.listings = response.data.lists.listings;
            console.log("props", response)
            setTimeout(() => {
                $("#AllPropertiesTable").DataTable();
            }, 10);

            });
        },
      },
      components : {
          Master,
      },
      mounted(){
        this.loadLists();
        this.user = localStorage.getItem('user');
        this.user = JSON.parse(this.user);

      }
    }
    </script>
    
    
    <style>
    .image-preview-container {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .preview-box {
        width: 80px;
        height: 80px;
        position: relative;
        border-radius: 6px;
        overflow: hidden;
        border: 1px solid #ddd;
    }

    .preview-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .remove-btn {
        position: absolute;
        top: 3px;
        right: 3px;
        background: rgba(255, 0, 0, 0.7);
        color: white;
        border: none;
        border-radius: 50%;
        width: 18px;
        height: 18px;
        font-size: 14px;
        line-height: 14px;
        text-align: center;
        cursor: pointer;
    }

    </style>    