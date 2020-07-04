<template>
    <div>
        <header-component>
            <template v-slot:title>Edit Teacher Account Details</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li  class="breadcrumb-item">Edit Teacher Account Details</li>
            </template>
        </header-component>
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                        <div class="card-header">
                            <h3 class="card-title">Edit Details</h3>
                        </div>
                        <form @submit.prevent="handleSubmit" action="#">
                            <div class="card-body">
                                <error-component :error="error"></error-component>
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input v-model="teacher_details.name" type="text" class="form-control" id="name" placeholder="Name" required>
                                </div>

                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input v-model="teacher_details.email" type="email" class="form-control" id="email" placeholder="example@email.com" required>
                                </div>

                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <input v-model="teacher_details.role" type="text" class="form-control" id="role" placeholder="Professor, AP, Lecture... etc" required>
                                </div>

                                <div class="form-group">
                                    <label for="department">Department</label>
                                    <input v-model="teacher_details.department" type="text" class="form-control" id="department" placeholder="CS, Physics... etc" required>
                                </div>

                                <div class="form-group">
                                    <label>Password</label>
                                    <router-link :to="{name: 'change_password'}" class="btn btn-warning ml-3">Change Password</router-link>
                                </div>
                            </div>
                            <div class="card-footer">
                              <button :disabled="!hasChanged" type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                    <!-- Modal -->
                </div>
            </div>
            <!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
    </div>
    <!-- /.content -->
</template>

<script>
    import { diff } from 'deep-diff/dist/deep-diff.min.js';
    export default {
        name: "EditTeacherInfo",
        props: ['auth'],
        data() {
            return {
                loaded: false,
                originalData: {},
                teacher_details: {
                    id: "",
                    name: "",
                    email: "",
                    role: "",
                    department: ""
                },
                error: {
                    title: null,
                    details: {},
                },
            }
        },
        created() {
            this.getTeacherInfo()
        },
        computed: {
            hasChanged() {
                if (!diff(this.teacher_details, this.originalData)) return false;

                return true;
            },
        },
        methods: {
            getTeacherInfo() {
                this.loaded = false
                axios.get('/api/teacher/get-info')
                .then(({data}) => {
                    this.originalData = data
                    this.teacher_details = {...this.originalData};
                    this.loaded = true
                })
            },
            handleSubmit() {
                this.loaded = false
                axios.post('/api/teacher/edit-info', this.teacher_details)
                .then(() => {
                    toastr.success('Account info edited successfully.', 'Success')
                    window.location.href = '/app';
                })
                .catch(error => {
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                    this.loaded = true
                })
            }
        }
    }
</script>

<style scoped>

</style>
