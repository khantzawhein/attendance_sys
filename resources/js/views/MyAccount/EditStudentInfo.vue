<template>
    <div>
        <header-component>
            <template v-slot:title>Home</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li  class="breadcrumb-item">Edit Student Account Details</li>
            </template>
        </header-component>
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default">
                        <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                        <div class="card-header">
                            <h3 class="card-title">Request form for change infomation</h3>
                        </div>
                        <form @submit.prevent="confirmSubmit" id="student_edit" action="#">
                            <div class="card-body">
                                <div v-if="isPending" class="alert alert-info">
                                  <h5><i class="icon fas fa-info"></i>Your request is being reviewed.</h5>
                                  If you want to cancel this request or need to change something, please contact to the administration.
                                </div>
                                <error-component :error="error"></error-component>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input v-model="student.email" type="email" id="email" class="form-control" placeholder="example@email.com" required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name:</label>
                                            <input v-model="student.name" type="text" id="name" class="form-control" placeholder="Kyaw Kyaw" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="father_name">Father Name:</label>
                                            <input v-model="student.father_name" type="text" id="father_name" class="form-control"  required placeholder="eg. U Mg Mg">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="batch">Batch:</label>
                                            <input v-model="student.batch" type="number" id="batch" class="form-control" required placeholder="eg. 2020">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>NRC:</label>
                                            <input v-model="student.nrc" type="text" id="nrc" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="urn">University Registration No</label>
                                            <input v-model="student.urn" id="urn" pattern=".*[0-9]" minlength="6" maxlength="6" type="text" class="form-control" name="urn" placeholder="eg. 123456" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Mobile No</label>
                                            <input v-model="student.phone" id="phone" pattern=".*[0-9]" type="number" class="form-control" placeholder="eg. 09790001234" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <button :disabled="!hasChanged"  type="submit" class="btn bg-gradient-primary">Request for change</button>
                                                <p class="text-blue">Note: Once you click "Request for change", the request will be sent to the administration team and will be reviewed for decision.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
          </div>
        </div>
    </div>
</template>

<script>
    import { diff } from 'deep-diff/dist/deep-diff.min.js';
    import swal from 'sweetalert';
    export default {
        name: "EditStudentInfo",
        data() {
            return {
                originalData: {},
                student: {},
                error: {
                    title: null,
                    details: {}
                },
                loaded: false,
                isPending: null
            }
        },
        mounted() {
            this.getStudentData()
        },
        computed: {
            hasChanged() {
                if (!diff(this.student, this.originalData.data)) return false;

                return true;
            },
        },
        methods: {
            getStudentData() {
                axios.get('/api/student/get-info')
                .then(response => {
                    this.loaded = true
                    this.originalData = response.data;
                    this.student = {...this.originalData.data}
                    this.isPending = this.originalData.isPending
                    if (this.isPending) {
                       $('#student_edit :input').prop('disabled', true)
                    }
                })
                .catch(error => {
                    this.loaded = true
                    this.error.title = this.error = error.response.data.message || error.message;
                })
            },
            handleSubmit() {
                this.loaded = false
                axios.post('/api/student/edit-info', this.student)
                .then(() => {
                    this.getStudentData();
                    toastr.success('Edit requested successfully.<br>Please wait for the admin approval.', 'Success')
                })
                .catch(error => {
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                    this.loaded = true
                })
            },
            confirmSubmit() {
                swal({
                  title: "Are you sure?",
                  text: "Once submitted, you will not be able to edit this!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willSubmit) => {
                  if (willSubmit) {
                      this.handleSubmit()
                  }
                });
            }
        }
    }
</script>

<style scoped>

</style>
