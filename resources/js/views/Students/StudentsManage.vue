<template>
    <div>
        <header-component>
            <template v-slot:title>Students</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item"><router-link :to="{name: 'students'}">Students</router-link></li>
                <li class="breadcrumb-item">Manage Student Account</li>
            </template>
        </header-component>
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="loading text-center align-items-center justify-content-center d-flex vh-100" v-if="!loaded">
                        <loader-component></loader-component>
                    </div>
                    <error-component :error="error"></error-component>
<!--                    card header-->
                    <div class="card card-default" v-if="loaded">
                        <div class="card-header">
                            <h3 class="card-title">Manage Student Account</h3>
                        </div>

                        <div class="card-body">
                            <form action="">
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
                                            <div class="col-md-6">
                                                <button :disabled="!hasChanged" @click="handleSubmit" type="button" class="btn bg-gradient-primary">Save</button>
                                                <button type="button" class="btn bg-gradient-danger" data-toggle="modal" data-target="#changePassword">Manage Password</button>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-2 ml-auto">
                                                        <button @click="deleteConfirm" type="button" class="btn bg-gradient-danger">Delete</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <change-password-component :loaded="changePasswordLoaded">
                                        <template v-slot:body>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="new-password">New Password</label>
                                                        <input v-model="credential.password" class="form-control" type="password" id="new-password" autocomplete="new-password">
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="password-confirmation">Confirm Password</label>
                                                        <input v-model="credential.password_confirmation" class="form-control" type="password" id="password-confirmation" autocomplete="off">
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                        <template v-slot:button>
                                            <button @click="changePassword" type="button" class="btn bg-gradient-danger">Change Password</button>
                                        </template>
                                    </change-password-component>
                                </div>
                            </form>
                        </div>
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
        name: "StudentsManage",

        data() {
            return {
                id: null,
                credential: {
                    password: "",
                    password_confirmation: ""
                },
                originalData: {},
                student: {},
                error: {
                    title: null,
                    details: {}
                },
                loaded: false,
                changePasswordLoaded: true
            }
        },
        created() {
            this.id = this.$route.params.id
            axios.get('/api/students/' + this.id)
            .then(response => {
                this.loaded = true
                this.originalData = response.data[0];
                this.student = {...this.originalData}
            })
            .catch(error => {
                this.loaded = true
                this.error.title = this.error = error.response.data.message || error.message;
            })
        },
        computed: {
            hasChanged() {
                if (!diff(this.student, this.originalData)) return false;

                return true;
            },
        },
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.put('/api/students/' + this.id, this.student)
                .then(response => {
                    this.$router.back()
                    toastr.success('Edit Successful.', 'Success')
                }).catch(error => {
                    this.loaded = true;
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                })
            },
            changePassword()
            {
                this.changePasswordLoaded = false
                axios.put('/api/students/' + this.id + '/change-password', this.credential)
                .then(response => {
                    this.changePasswordLoaded = true
                    toastr.success('Password Changed.', 'Success');
                    $('#changePassword').modal('hide');
                    this.credential.password = this.credential.password_confirmation = ""
                })
                .catch(error => {
                    let message = error.response.data.errors.password.join('<br>') || error.message
                    this.changePasswordLoaded = true
                    toastr.error(message, 'Validation failed')
                })
            },
            handleDelete() {
                this.loaded = false;
                axios.delete('/api/students/' + this.id)
                .then(response => {
                    swal("Record has been deleted.", {
                      icon: "success",
                    });
                    this.$router.back()
                })
                .catch(error => {
                    this.loaded = true
                    this.error.title = error.response.data.message || error.message;
                })
            },
            deleteConfirm() {
                swal({
                  title: "Are you sure?",
                  text: "This action will delete this student account and its attendance data.\n\nAre you sure want to proceed?",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                      this.handleDelete()
                  }
                });
            },
        }
    }
</script>

<style scoped>

</style>
