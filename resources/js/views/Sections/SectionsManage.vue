<template>
    <div>
        <header-component>
            <template v-slot:title>Edit Section</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item"><router-link :to="{name: 'sections'}">Sections</router-link></li>
                <li class="breadcrumb-item">Edit Section</li>
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
                    <timetable-component></timetable-component>
                    <error-component :error="error"></error-component>
<!--                    card header-->
                    <div class="card card-default" v-show="loaded">
                        <div class="card-header">
                            <h3 class="card-title">Edit Section</h3>
                        </div>

                        <div class="card-body">
                            <form action="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Section Name</label>
                                            <input type="text" id="name" class="form-control" v-model="section.name" required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="year">Year or Description</label>
                                            <input type="text" id="year" class="form-control" v-model="section.year" required autocomplete="off" placeholder="eg. First Year">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="startTime">Class Start Time:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                </div>
                                                <input v-model="section.start_time" type="text" class="form-control float-right" id="startTime" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="endTime">Class End Time:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                </div>
                                                <input v-model="section.end_time" type="text" class="form-control float-right" id="endTime" required>
                                            </div>
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
        name: "SectionsManage",
        data() {
            return {
                id: null,
                section: {},
                originalData: {},
                error: {
                    title: null,
                    details: {}
                },
                loaded: false,
            }
        },
        mounted() {
            var vm = this;
            $('#startTime').timepicker({ 'step': 15, scrollDefault: '8:45am' }).on('changeTime', function() {
                vm.formData.start_time = $("#startTime").val()
            });
            $('#endTime').timepicker({ 'step': 15, scrollDefault: '8:45am'}).on('changeTime', function() {
                vm.formData.end_time = $("#endTime").val()
            });
        },
        created() {
            this.id = this.$route.params.id
            axios.get('/api/sections/' + this.id)
                .then(response => {
                    this.loaded = true
                    this.originalData = response.data.data;
                    this.section = {...this.originalData}
                })
                .catch(error => {
                    this.loaded = true
                    this.error.title = this.error = error.response.data.message || error.message;
                })
        },
        computed: {
            hasChanged() {
                if (!diff(this.section, this.originalData)) return false;

                return true;
            },
        },
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.put('/api/sections/' + this.id, this.section)
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
                axios.delete('/api/sections/' + this.id)
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
