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
                      <h5 class="card-title">{{property.title}} Units <span>| Property units</span></h5>
                      <p class="card-text"> 
                      <div class="row">
                          <div class="col d-flex">                
                          <a
                            class="btn btn-sm btn-primary rounded-pill active"
                            style="background-color: darkgreen; border-color: darkgreen;"
                            @click="addUnit()"
                          >
                            Add Unit
                          </a>
                          </div>
                          <div class="col-auto d-flex justify-content-end">
                          <div class="btn-group" role="group">
                              <button id="btnGroupDrop1" type="button" style="background-color: darkgreen; border-color: darkgreen;" class="btn btn-sm btn-primary rounded-pill dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="ri-add-line"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                                     <a @click="navigateTo('/managedproperties' )" class="dropdown-item" href="#"><i class="ri-building-fill mr-2"></i>Properties</a>
                                     <a @click="navigateTo('/pmstenants' )" class="dropdown-item" href="#"><i class="ri-user-fill mr-2"></i>Tenants</a>
                                    <a @click="navigateTo('/pmslandlords' )" class="dropdown-item" href="#"><i class="ri-user-fill mr-2"></i>Landlords</a>
                                </div>
                              </div>
                            </div>
                        </div>
            
                      </p>
    
                      <table id="AllPropertiesTable" class="table table-borderless">
                        <thead>
                          <tr>
                            <!--<th scope="col">Preview</th>-->
                            <th scope="col">Unit Number</th>
                            <th scope="col">Type</th>
                            <th scope="col">Deposit</th>
                            <th scope="col">Rent(monthly)</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-for="item in units" :key="item.id">
                            <!--<th scope="row"><a href="#">
                              <img :src="getPhoto() + property.images[0].name" />
                            </a></th>-->
                            <!-- <td>{{property["images"][0]["name"]}}</td> -->
                            <td>{{item.unit_number ?? 'N/A'}}</td>
                            <td>{{item.type ?? 'N/A'}}</td>
                            <td>{{item.deposit ?? 'N/A'}}</td>
                            <td>{{item.monthly_rent ?? 'N/A'}}</td>
                            <td>
                              <!-- VACANT -->
                              <span v-if="item.status == 'vacant'" class="badge bg-success">
                                <i class="bi bi-door-open me-1"></i> Vacant
                              </span>

                              <!-- RENTED -->
                              <span v-else-if="item.status == 'rented'" class="badge bg-primary">
                                <i class="bi bi-house-check me-1"></i> Rented
                              </span>

                              <!-- MAINTENANCE -->
                              <span v-else-if="item.status == 'maintenance'" class="badge bg-danger">
                                <i class="bi bi-tools me-1"></i> Maintenance
                              </span>
                            </td>

                            <td>
                              <div class="btn-group" role="group">
                                  <button id="btnGroupDrop1" type="button" style="background-color: darkgreen; border-color: darkgreen;" class="btn btn-sm btn-primary rounded-pill dropdown-toggle" data-toggle="dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Action
                                  </button>
                                  <div class="dropdown-menu" aria-labelledby="btnGroupDrop1" style="">
                                  <a @click="viewUnit(item )" class="dropdown-item" href="#"><i class="ri-eye-fill mr-2"></i>View</a> 
                                  <a @click="editUnit(item)" class="dropdown-item" href="#"><i class="ri-pencil-fill mr-2"></i>Edit</a>                                           
                                  <a @click="deleteUnit(item.id)" class="dropdown-item" href="#"><i class="ri-delete-bin-line mr-2"></i>Delete</a>
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
                <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h5 class="modal-title" id="viewModalLabel">Unit Info</h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">          

                          <p><strong>Unit Number:</strong> {{selectedUnit.unit_number}}
                            <span v-if="selectedUnit.status == 0" class="badge bg-warning text-dark"><i class="bi bi-exclamation-triangle me-1"></i> Vacant</span>   
                            <span v-else-if="selectedUnit.status == 1" class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Rented</span>
                            <span v-else class="badge bg-light text-dark"><i class="bi bi-star me-1"></i> Closed</span> </p>
                          <p v-if="selectedUnit.type"><strong>Type:</strong> {{selectedUnit.type}} </p>
                          <p v-if="selectedUnit.monthly_rent"><strong>Monthly Rent:</strong> {{selectedUnit.monthly_rent}} </p>
                          <p v-if="selectedUnit.deposit"><strong>Rent Deposit:</strong> {{selectedUnit.deposit}}</p>
                          <p v-if="selectedUnit.electricity_deposit"><strong>Electricity Deposit:</strong> {{selectedUnit.electricity_deposit}} </p>
                          <p v-if="selectedUnit.water_deposit"><strong>Water Deposit:</strong> {{selectedUnit.water_deposit}} </p>
                          <p v-if="selectedUnit.garbage_fee"><strong>Garbage Fee:</strong> {{selectedUnit.garbage_fee}} </p>
                          <p v-if="selectedUnit.security_fee"><strong>Security Fee:</strong> {{selectedUnit.security_fee}} </p>
                          <p>(*all charges in KES.)</p>
                          
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                      </div>
                    </div>
                </div>

                <!--Add Unit Modal -->
                <div class="modal fade" id="AddUnitModal" tabindex="-1" aria-labelledby="AddUnitModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="AddUnitModalLabel">Add Unit</h5>
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
                                          <label for="inputPassword" class="form-label">Unit Number*</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="text"
                                                placeholder="Unit Number"
                                                id="unit_number"
                                                name="unit_number"
                                                v-model="data.unit_number"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback" v-if="!data.unit_number">Please enter unit number!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Type*</label>
                                          <div class="col-sm-12">
                                            <select name="type" v-model="data.type" class="form-select" id="type">
                                                <option value="0" selected disabled>Select Type</option>
                                                <option value="Residential">Residential</option>
                                                <option value="Commercial">Commercial</option>
                    
                                            </select>
                                            <div class="invalid-feedback" v-if="!data.type">Please select type!</div>
                                          </div>
                                      </div>
                    
                                    </div>
                                    <div class="row mb-3"></div>
                                    <div class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Rent Deposit*</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Deposit"
                                                id="deposit"
                                                name="deposit"
                                                v-model="data.deposit"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback" v-if="!data.deposit">Please enter deposit!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Monthly Rent*</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Monthly Rent"
                                                id="monthly_rent"
                                                name="monthly_rent"
                                                v-model="data.monthly_rent"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback" v-if="!data.monthly_rent">Please enter monthly rent!</div>
                                          </div>
                                      </div>

                                    </div>
                                    <div class="row mb-3"></div>
                                    <div class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Monthly Garbage Collection Fee</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Optional e.g 500"
                                                id="title"
                                                name="title"
                                                v-model="data.garbage_fee"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Monthly Security Fee</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Optional e.g 500"
                                                id="title"
                                                name="title"
                                                v-model="data.security_fee"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>

                                    </div>
                                    <div class="row mb-3"></div>
                                    <div class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Electricity Deposit</label>
                                          <div class="col-sm-102">
                                            <input
                                                type="number"
                                                placeholder="Optional e.g 500"
                                                id="title"
                                                name="title"
                                                v-model="data.electricity_deposit"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Water Deposit</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Optional e.g 500"
                                                id="title"
                                                name="title"
                                                v-model="data.water_deposit"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>

                                    </div>
                                    <div class="row mb-3"></div>
                                    <div class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Electricity Meter No</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Optional"
                                                id="title"
                                                name="title"
                                                v-model="data.electricity_meter"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Water Meter No</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Optional"
                                                id="title"
                                                name="title"
                                                v-model="data.water_meter"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>

                                    </div>
                                    <div class="row mb-3"></div>
                                    <div v-if="victoriaId == 5" class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Paybill Number</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Enter Paybill No."
                                                id="paybill_number"
                                                name="paybill_number"
                                                v-model="data.paybill_number"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter paybill number!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Account Number</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Enter Account No."
                                                id="account_number"
                                                name="account_number"
                                                v-model="data.account_number"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter account number!</div>
                                          </div>
                                      </div>

                                    </div>                                    
                                </div>                                

                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" style="background-color: darkgreen; border-color: darkgreen;" class="btn btn-primary" @click.prevent="submit()">Save changes</button>
                              </div>
                            </div>
                          </div>
                </div>

                <!--Edit Unit Modal -->
                <div class="modal fade" id="EditUnitModal" tabindex="-1" aria-labelledby="EditUnitModalLabel" aria-hidden="true">
                          <div class="modal-dialog">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="AddUnitModalLabel">Edit Unit</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                              </div>
                              <div class="modal-body">
    
                                  <div class="row m-auto p-auto justify-content- g-3 needs-validation" novalidate="" autocomplete="off">
                                    <div class="row  mb-3"></div>
                    
                                    <div class="form-group row">
                                      <input
                                          type="hidden"
                                          id="user_id"
                                          name="user_id"
                                          value="1"
                                          class="form-control"
                                      />
                                        <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Unit Number*</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="text"
                                                placeholder="Unit Number"
                                                id="edit_unit_number"
                                                name="unit_number"
                                                v-model="form.unit_number"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback" v-if="!form.unit_number">Please enter unit number!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Type*</label>
                                          <div class="col-sm-12">
                                            <select name="type" v-model="form.type" class="form-select" id="edit_type">
                                                <option value="0" selected disabled>Select Type</option>
                                                <option value="Residential">Residential</option>
                                                <option value="Commercial">Commercial</option>
                    
                                            </select>
                                            <div class="invalid-feedback" v-if="!form.type">Please select type!</div>
                                          </div>
                                      </div>
                    
                                    </div>
                                    <div class="row mb-3"></div>
                                    <div class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Deposit*</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Deposit"
                                                id="edit_deposit"
                                                name="deposit"
                                                v-model="form.deposit"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback" v-if="!form.deposit">Please enter deposit!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Monthly Rent*</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Monthly Rent"
                                                id="edit_monthly_rent"
                                                name="monthly_rent"
                                                v-model="form.monthly_rent"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback" v-if="!form.monthly_rent">Please enter monthly rent!</div>
                                          </div>
                                      </div>

                                    </div>
                                    <div class="row mb-3"></div>
                                    <div class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Monthly Garbage Collection Fee</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Optional e.g 500"
                                                id="title"
                                                name="title"
                                                v-model="form.garbage_fee"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Monthly Security Fee</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Optional e.g 500"
                                                id="title"
                                                name="title"
                                                v-model="form.security_fee"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>

                                    </div>
                                    <div class="row mb-3"></div>
                                    <div class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Electricity Deposit</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Optional e.g 500"
                                                id="title"
                                                name="title"
                                                v-model="form.electricity_deposit"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Water Deposit</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Optional e.g 500"
                                                id="title"
                                                name="title"
                                                v-model="form.water_deposit"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>

                                    </div>
                                    <div class="row mb-3"></div>                
                                    <div class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Electricity Meter No</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Electricity Meter No"
                                                id="title"
                                                name="title"
                                                v-model="form.electricity_meter"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>
                                      <div class="col-sm-6">
                                          <label for="inputPassword" class="form-label">Water Meter No</label>
                                          <div class="col-sm-12">
                                            <input
                                                type="number"
                                                placeholder="Water Meter No"
                                                id="title"
                                                name="title"
                                                v-model="form.water_meter"
                                                class="form-control"
                                                required=""
                                            />
                                            <div class="invalid-feedback">Please enter title!</div>
                                          </div>
                                      </div>

                                    </div>
                                    <div class="row mb-3"></div>
                                    <div v-if="victoriaId == 5" class="form-group row">
                                      <div class="col-sm-6">
                                          <label for="title" class="form-label">Paybill Number*</label>
                                          <div class="col-sm-12">
                                              <input type="text" placeholder="Paybill Number" id="title" v-model="form.paybill_number" name="title" class="form-control"
                                                required />
                                              <div class="invalid-feedback" v-if="!form.name">Please enter name</div>
                                          </div>
                                        </div>              
                                      <div class="col-sm-6">
                                          <label for="title" class="form-label">Account Number*</label>
                                          <div class="col-sm-12">
                                              <input type="text" placeholder="Account Number" id="title" v-model="form.account_number" name="title" class="form-control"
                                                required />
                                              <div class="invalid-feedback" v-if="!form.name">Please enter name</div>
                                          </div>
                                        </div>
                                  
                                      </div>
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
          properties: [],
          units: [],
          propertytypes: [],
          selectedUnit: [],
          user: [],
          property: [],
          victoriaId: '',
          form: {
            unit_number: '',
            type: '',
            deposit: '',
            monthly_rent: '',
            garbage_fee: '0.00',
            security_fee: '0.00',
            electricity_deposit: '0.00',
            water_deposit: '0.00',
            paybill_number: '',
            account_number: ''          
          },
          data: {
            unit_number: '',
            type: '',
            deposit: '',
            monthly_rent: '',
            garbage_fee: '',
            security_fee: '',
            electricity_deposit: '',
            water_deposit: '',
            paybill_number: '',
            account_number: ''
          
          },
          addUnitPermission: '',
          editUnitPermission: '',
          deleteUnitPermission: ''
        }
      },
      methods: {
        viewUnit(property)
        {
          this.selectedUnit = property;
          console.log("pussy",this.selectedUnit)
          // Show the modal after fetching data
          const modal = new bootstrap.Modal(document.getElementById('viewModal'));
          modal.show();
        },
        editUnit(property)
        {
          this.form = property;
          // Show the modal after fetching data
          const modal = new bootstrap.Modal(document.getElementById('EditUnitModal'));
          modal.show();
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
        validateFormChanges() {
          let isValid = true;
          if (!this.form.unit_number) {
              isValid = false;
              document.getElementById('edit_unit_number').classList.add('is-invalid');
          } else {
              document.getElementById('edit_unit_number').classList.remove('is-invalid');
          }
          if (!this.form.type) {
              isValid = false;
              document.getElementById('edit_type').classList.add('is-invalid');
          } else {
              document.getElementById('edit_type').classList.remove('is-invalid');
          }
          if (!this.form.deposit) {
              isValid = false;
              document.getElementById('edit_deposit').classList.add('is-invalid');
          } else {
              document.getElementById('edit_deposit').classList.remove('is-invalid');
          }
          if (!this.form.monthly_rent) {
              isValid = false;
              document.getElementById('edit_monthly_rent').classList.add('is-invalid');
          } else {
              document.getElementById('edit_monthly_rent').classList.remove('is-invalid');
          }
          return isValid;
       },             
       async submitFormChanges() {
            try {
                // Update the unit
                const response = await axios.put(`/api/units/${this.form.id}`, this.form);
                console.log(response);

                // Notify the user
                toast.fire('Success!', 'Unit updated!', 'success');

                // Navigate to the units list
                const modal = bootstrap.Modal.getInstance(document.getElementById('EditUnitModal'));
                modal.hide();
                // this.form = '';
                this.loadLists();

            } catch (error) {
                console.error("Error updating unit:", error);
                toast.fire('Error!', 'Failed to update unit. Please try again.', 'error');
            }
        },
        addUnit()
        {
          // Show the modal after fetching data
          const modal = new bootstrap.Modal(document.getElementById('AddUnitModal'));
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
          if (!this.data.unit_number) {
              isValid = false;
              document.getElementById('unit_number').classList.add('is-invalid');
          } else {
              document.getElementById('unit_number').classList.remove('is-invalid');
          }
          if (!this.data.type) {
              isValid = false;
              document.getElementById('type').classList.add('is-invalid');
          } else {
              document.getElementById('type').classList.remove('is-invalid');
          }
          if (!this.data.deposit) {
              isValid = false;
              document.getElementById('deposit').classList.add('is-invalid');
          } else {
              document.getElementById('deposit').classList.remove('is-invalid');
          }
          if (!this.data.monthly_rent) {
              isValid = false;
              document.getElementById('monthly_rent').classList.add('is-invalid');
          } else {
              document.getElementById('monthly_rent').classList.remove('is-invalid');
          }
          return isValid;
       },
       async submitForm() {
          if (this.validateForm()) {
            this.submitting = true;
            try {
              // Step 1: Create the unit and wait for the response
              const response = await axios.post(`/api/units/${this.$route.params.id}`, this.data);
              
              // Step 2: Capture the created unit ID
              this.createdUnitId = response.data.unit.id;
              console.log("Created Unit ID:", this.createdUnitId);

              // Display success message
              toast.fire('Success!', 'Unit added!', 'success');

              // Step 4: Redirect to units list
              const modal = bootstrap.Modal.getInstance(document.getElementById('AddUnitModal'));
              modal.hide();
              this.data = '';
              this.loadLists();
            } catch (error) {
              console.error("Submission error:", error);
              // Optionally handle errors with a toast message
              toast.fire('Error!', error.response?.data?.message || 'Failed to add unit', 'error');
            } finally {
              this.submitting = false;
            }
          }
        },
        getPhoto()
        {
            return "/storage/properties/";
        },
        navigateTo(location){
            this.$router.push(location)
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
        getProperty() {
             axios.get('/api/properties/'+this.$route.params.id).then((response) => {
     
             this.property = response.data;
             console.log("props", response)
    
             });
        },
        deleteUnit(id){
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
                  axios.delete('/api/pmsunit/'+id).then(() => {
                  toast.fire(
                    'Deleted!',
                    'Unit has been deleted.',
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
            axios
                .get(`/api/properties/${this.$route.params.id}/units`)
                .then((response) => {

                    // If you used structured JSON:
                    // this.units = response.data.units;

                    // If your controller returns raw collection:
                    this.units = response.data.units;

                    console.log("Units list:", response.data);

                    setTimeout(() => {
                        $("#AllPropertiesTable").DataTable();
                    }, 10);

                    toast.fire(
                        'Reminder!',
                        'Please update details (if necessary)',
                        'warning'
                    );
                })
                .catch((error) => {
                    console.error("Error loading units:", error);
                    toast.fire('Error', 'Failed to load units', 'error');
                });
        }

      },
      components : {
          Master,
      },
      mounted(){
        this.loadLists();
        this.getProperty();
        // this.victoriaId = this.$route.params.id;
        // this.user = localStorage.getItem('user');
        // this.user = JSON.parse(this.user);
        // this.userId = this.user.id;
        // this.getUserPermissions(this.userId);
        // this.currentUser = JSON.parse(localStorage.getItem('user')) || {};
        // this.current_user_id = this.currentUser.id;
        // this.current_user = this.currentUser.first_name + " " + this.currentUser.last_name;

      }
    }
    </script>
    
    
    