<template>
    <div>
        <header-component>
            <template v-slot:title>Teachers</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item"><router-link :to="{name: 'teachers'}">Teachers</router-link></li>
                <li class="breadcrumb-item">Manage Teacher Account</li>
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
                    <div class="error mt-4" v-if="error" >
                        <div class="alert alert-danger" role="alert">
                            <div class="alert alert-danger" role="alert">
                            <strong>{{error}}</strong>
                            <ul v-for="(error, index) in errorDetails">
                                <li>
                                    <strong>{{index}}</strong>
                                    <ul>
                                        <li v-for="suberror in error">
                                            {{suberror}}
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                        </div>
                    </div>
<!--                    card header-->
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title">Manage Teacher Account</h3>
                        </div>

                        <div class="card-body">
                            <form action="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email:</label>
                                            <input v-model="teachers.email" type="email" id="email" class="form-control" placeholder="example@email.com" required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Name:</label>
                                            <input v-model="teachers.name" type="text" id="name" class="form-control" placeholder="Kyaw Kyaw" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="department">Department:</label>
                                            <input v-model="teachers.department" type="text" id="department" class="form-control" placeholder="Computer science, physics,..." required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">Role:</label>
                                            <input v-model="teachers.role" type="text" id="role" class="form-control" placeholder="Professor, AP, Lecturer.. etc" required>
                                        </div>
                                    </div>
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
    export default {
        name: "TeacherManage",

        data() {
            return {
                id: null,
                credential: {
                    password: "",
                    password_confirmation: ""
                },
                originalData: {},
                teachers: {},
                error: null,
                errorDetails: "",
                loaded: false,
                changePasswordLoaded: true
            }
        },
        created() {
            this.id = this.$route.params.id
            axios.get('/api/teachers/' + this.id)
            .then(response => {
                this.loaded = true
                this.originalData = response.data[0];
                this.teachers = {...this.originalData}
            })
            .catch(error => {
                this.loaded = true
                this.error = this.error = error.response.data.message || error.message;
            })
        },
        computed: {
            hasChanged() {
                if (!diff(this.teachers, this.originalData)) return false;

                return true;
            }
        },
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.put('/api/teachers/' + this.id, this.teachers)
                .then(response => {
                    this.$router.push({name: "teachers"})
                    toastr.success('Edit Successful.', 'Success')
                }).catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                    this.errorDetails = error.response.data.errors;
                })
            },
            changePassword()
            {
                this.changePasswordLoaded = false
                axios.put('/api/teachers/' + this.id + '/change-password', this.credential)
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
                axios.delete('/api/teachers/' + this.id)
                .then(response => {
                    swal("Record has been deleted.", {
                      icon: "success",
                    });
                    this.$router.push({name: 'teachers'})
                })
                .catch(error => {
                    this.loaded = true
                    this.error = error.response.data.message || error.message;
                })
            },
            deleteConfirm() {
                swal({
                  title: "Are you sure?",
                  text: "Once deleted, you will not be able to recover this!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                      this.handleDelete()
                  }
                });
            }
        }
    }
</script>

<style scoped>

</style>
