<template>
    <div>
    <header-component>
        <template v-slot:title>Create a Course</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item"><router-link :to="{name: 'teachers'}">Teachers</router-link></li>
            <li class="breadcrumb-item active">Create Teacher Account</li>
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
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Create Teacher Account</h3>
                    </div>
                    <div class="card-body">
                        <form action="" @submit.prevent="handleSubmit">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input v-model="formData.email" type="email" id="email" class="form-control" placeholder="example@email.com" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input v-model="formData.name" type="text" id="name" class="form-control" placeholder="Kyaw Kyaw" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department">Department:</label>
                                        <input v-model="formData.department" type="text" id="department" class="form-control" placeholder="Computer science, physics,..." required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Role:</label>
                                        <input v-model="formData.role" type="text" id="role" class="form-control" placeholder="Professor, AP, Lecturer.. etc" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">New Password:</label>
                                        <input v-model="formData.password" type="password" id="password" class="form-control" placeholder="New Password" autocomplete="new-password" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password_confirmation">Confirm Password:</label>
                                        <input v-model="formData.password_confirmation" type="password" id="password_confirmation" class="form-control" placeholder="Confirm Password" autocomplete="new-password" required>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn d-block bg-gradient-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content -->
  </div>
</template>
<script>
    export default {
        data() {
            return {
                formData: {
                    email: "",
                    name: "",
                    department: "",
                    role: "",
                    password: "",
                    password_confirmation: ""
                },
                error: null,
                errorDetails: "",
                loaded: true
            }
        },
        created() {
        },
        methods: {

            handleSubmit() {
                this.loaded = false;
                axios.post('/api/teachers', this.formData)
                .then(response => {
                    toastr.success('Account creation successful.', 'Success')
                    this.$router.push({name: 'teachers'})
                }).catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                    this.errorDetails = error.response.data.errors;
                });
            }
        }
    }

</script>
