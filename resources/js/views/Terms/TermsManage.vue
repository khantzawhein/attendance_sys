<template>
    <div>
        <header-component>
            <template v-slot:title>Edit Term</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item"><router-link :to="{name: 'terms'}">Terms</router-link></li>
                <li class="breadcrumb-item">Edit Term</li>
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
                            <h3 class="card-title">Edit Term</h3>
                        </div>

                        <div class="card-body">
                            <form action="">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="name">Term Name</label>
                                            <input type="text" id="name" class="form-control" v-model="term.name" required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date">Start Date</label>
                                            <input type="text" id="start_date" class="form-control" data-language='en' data-date-format="yyyy/mm/dd" v-model="term.start_date" required autocomplete="off" placeholder="yyyy/mm/dd">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date">End Date</label>
                                            <input type="text" id="end_date" class="form-control" data-language="en" data-date-format="yyyy/mm/dd" v-model="term.end_date" required autocomplete="off" placeholder="yyyy/mm/dd">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button :disabled="!hasChanged" @click="handleSubmit" type="button" class="btn bg-gradient-primary">Save</button></div>
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
        name: "TermsManage",
        data() {
            return {
                id: null,
                term: {},
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
            axios.get('/api/terms/' + this.id)
                .then(response => {
                    this.loaded = true
                    this.originalData = response.data;
                    this.term = {...this.originalData}
                })
                .catch(error => {
                    this.loaded = true
                    this.error.title = this.error = error.response.data.message || error.message;
                })
        },
        mounted() {
            $('#start_date').datepicker({
                onSelect: function(formattedDate, date, inst) {
                    this.term.start_date = formattedDate
                }.bind(this)
            })
            $('#end_date').datepicker({
                onSelect: function(formattedDate, date, inst) {
                    this.term.end_date = formattedDate
                }.bind(this)
            })
        },
        computed: {
            hasChanged() {
                if (!diff(this.term, this.originalData)) return false;

                return true;
            },
        },
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.put('/api/terms/' + this.id, this.student)
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
                axios.delete('/api/terms/' + this.id)
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
