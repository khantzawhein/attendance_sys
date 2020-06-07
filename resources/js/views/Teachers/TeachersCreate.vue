<template>
    <div>
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
        <div class="row">
            <div class="col-md-12 mt-3">
                <h4 class="mb-3">Create a new teacher account</h4>
                <form action="#" @submit.prevent="handleForm">
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input v-model="formData.email" type="email" id="email" class="form-control" placeholder="example@email.com" required>
                    </div>
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input v-model="formData.name" type="text" id="name" class="form-control" placeholder="Kyaw Kyaw" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <input v-model="formData.role" type="text" id="role" class="form-control" placeholder="Professor, AP, Lecturer.. etc" required>
                    </div>
                    <div class="form-group">
                        <label for="department">Department:</label>
                        <input v-model="formData.department" type="text" id="department" class="form-control" placeholder="Computer science, physics,..." required>
                    </div>
                    <div class="form-group">
                        <label for="password">New Password:</label>
                        <input v-model="formData.password" type="password" id="password" class="form-control" placeholder="New Password" autocomplete="new-password" required>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password:</label>
                        <input v-model="formData.password_confirmation" type="password" id="password_confirmation" class="form-control" placeholder="Confirm Password" autocomplete="new-password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
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

            handleForm() {
                this.loaded = false;
                axios.post('/api/teachers', this.formData)
                .then(response => {
                    Bus.$emit('flash-success', "Successfully created.")
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
