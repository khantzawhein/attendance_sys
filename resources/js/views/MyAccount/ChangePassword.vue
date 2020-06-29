<template>
    <div>
        <header-component>
            <template v-slot:title>Change Password</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item">Change Password</li>
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
                            <h3 class="card-title">Change Password</h3>
                        </div>
                        <form @submit.prevent="handleSubmit" action="#">
                            <div class="card-body">
                                <error-component :error="error"></error-component>
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input v-model="credential.current_password" type="password" class="form-control" id="current_password" placeholder="Current Password" required autocomplete="current_password">
                                </div>
                                <div class="form-group">
                                    <label for="new_password">Password</label>
                                    <input v-model="credential.password" type="password" class="form-control" id="new_password" placeholder="New Password" required autocomplete="new_password">
                                </div>
                                <div class="form-group">
                                    <label for="confirm_password">Confirm Password</label>
                                    <input v-model="credential.password_confirmation" type="password" class="form-control" id="confirm_password" placeholder="Confirm Password" required autocomplete="new_password">
                                </div>
                            </div>
                            <div class="card-footer">
                              <button type="submit" class="btn btn-primary">Submit</button>
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
    export default {
        name: "ChangePassword",

        data() {
            return {
                loaded: true,
                credential: {
                    current_password: "",
                    password: "",
                    password_confirmation: ""
                },
                error: {
                    title: null,
                    details: {}
                },
            }
        },
        methods: {
            handleSubmit()
            {
                if (this.credential.password != this.credential.password_confirmation) {
                    toastr.error('Passwords do not match.', 'Vaildation Failed')
                    return false
                }
                this.loaded = false
                axios.post('/api/user/change-password', this.credential)
                .then(() => {
                    toastr.success('Password changed successfully', 'Success')
                    this.$router.push('/app');
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
