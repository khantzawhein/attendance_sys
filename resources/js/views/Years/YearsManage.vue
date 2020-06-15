<template>
    <div>
        <header-component>
            <template v-slot:title>Edit Year</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item"><router-link :to="{name: 'years'}">Years</router-link></li>
                <li class="breadcrumb-item">Edit Year</li>
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
                    <div class="card card-default" v-show="loaded">
                        <div class="card-header">
                            <h3 class="card-title">Edit Year</h3>
                        </div>

                        <div class="card-body">
                            <form action="" @submit.prevent="handleSubmit">
                                <div class="row">
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="academic_year">Academic Year</label>
                                            <input type="number" id="academic_year" class="form-control" v-model="year.academic_year" required autocomplete="off" placeholder="eg. 2021">
                                        </div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="form-group">
                                            <label for="year">Name</label>
                                            <input type="text" id="year" class="form-control" v-model="year.name" required autocomplete="off" placeholder="eg. First Year">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button :disabled="!hasChanged"  type="submit" class="btn bg-gradient-primary">Save</button></div>
                                            <div class="col-md-6">
                                                <div class="row">
                                                    <div class="col-2 ml-auto">
                                                        <button @click="deleteConfirm" type="button" class="btn bg-gradient-danger">Delete</button>
                                                    </div>

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
    </div>
</template>

<script>
    import { diff } from 'deep-diff/dist/deep-diff.min.js';
    export default {
        name: "YearsManage",
        data() {
            return {
                id: null,
                year: {},
                originalData: {},
                error: {
                    title: null,
                    details: {}
                },
                loaded: false,
            }
        },
        created() {
            this.id = this.$route.params.id
            axios.get('/api/years/' + this.id)
                .then(response => {
                    this.loaded = true
                    this.originalData = response.data;
                    this.year = {...this.originalData}
                })
                .catch(error => {
                    this.loaded = true
                    this.error.title = this.error = error.response.data.message || error.message;
                })
        },
        computed: {
            hasChanged() {
                if (!diff(this.year, this.originalData)) return false;

                return true;
            },
        },
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.put('/api/years/' + this.id, this.year)
                    .then(response => {
                        this.$router.back()
                        toastr.success('Edit Successful.', 'Success')
                    }).catch(error => {
                    this.loaded = true;
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                })
            },
            handleDelete() {
                this.loaded = false;
                axios.delete('/api/years/' + this.id)
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
            },
        }
    }
</script>

<style scoped>

</style>
