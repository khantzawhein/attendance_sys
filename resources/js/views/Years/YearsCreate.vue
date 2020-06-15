<template>
    <div>
    <header-component>
        <template v-slot:title>Create a Year</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item"><router-link :to="{name: 'years'}">Years</router-link></li>
            <li class="breadcrumb-item active">Create Year</li>
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
                <div class="card card-default" v-show="loaded">
                    <div class="card-header">
                        <h3 class="card-title">Create Year</h3>
                    </div>
                    <div class="card-body">
                        <form action="" @submit.prevent="handleSubmit">
                            <div class="row justify-content-center">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="academic_year">Academic Year</label>
                                        <input type="number" id="academic_year" class="form-control" v-model="formData.academic_year" required autocomplete="off" placeholder="eg. 2021">
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="year">Name</label>
                                        <input type="text" id="year" class="form-control" v-model="formData.name" required autocomplete="off" placeholder="eg. First Year">
                                    </div>
                                </div>

                                <div class="col-md-10">
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
        name: "YearsCreate",
        data() {
            return {
                formData: {
                    name: "",
                    start_date: "",
                    end_date: ""
                },
                error: {
                    title: null,
                    details: {}
                },
                loaded: true
            }
        },
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.post('/api/years', this.formData)
                .then(response => {
                    this.loaded = true
                    toastr.success('Year creation successful.', 'Success')
                    this.$router.push({name: 'years'})
                })
                .catch(error => {
                    this.loaded = true
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                })
            }
        }
    }
</script>

<style scoped>

</style>
