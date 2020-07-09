<template>
    <div>
        <header-component>
            <template v-slot:title>Edit Class</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item"><router-link :to="{name: 'sections'}">Classes</router-link></li>
                <li class="breadcrumb-item">Edit Class</li>
            </template>
        </header-component>
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <timetable-component :auth="auth"></timetable-component>
<!--                    card header-->
                    <div class="card card-default" v-if="auth==3">
                        <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                        <div class="card-header">
                            <h3 class="card-title">Edit Class</h3>
                            <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                            </div>
                        </div>

                        <div class="card-body">
                            <error-component :error="error"></error-component>
                            <form action="">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Class Name</label>
                                            <input type="text" id="name" class="form-control" v-model="section.name" required autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                           <label>Semester</label>
                                            <select-component style="width: 100%;" :options="semesterOptions" v-model="section.semester_id">
                                                <option value="" selected class="default">Select Semester:</option>
                                            </select-component>
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
                <div class="col-12">
                    <section-students-component :section="originalData"></section-students-component>
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
        props: ['auth'],
        data() {
            return {
                id: null,
                section: [],
                originalData: [],
                error: {
                    title: null,
                    details: {}
                },
                loadStatus: {
                    sectionLoaded: false,
                    semesterLoaded: false
                },
                semesterOptions: []
            }
        },
        mounted() {
            var vm = this;
            $('#startTime').timepicker({ 'step': 15, scrollDefault: '8:45am' }).on('changeTime', function() {
                vm.section.start_time = $("#startTime").val()
            });
            $('#endTime').timepicker({ 'step': 15, scrollDefault: '8:45am'}).on('changeTime', function() {
                vm.section.end_time = $("#endTime").val()
            });
        },
        created() {
            this.id = this.$route.params.id
            axios.get('/api/sections/' + this.id)
                .then(response => {
                    this.loadStatus.sectionLoaded = true
                    this.originalData = response.data.data;
                    this.section = {...this.originalData}
                })
                .catch(error => {
                    this.loadStatus.sectionLoaded = true
                    this.error.title = this.error = error.response.data.message || error.message;
                })
            this.getSemesterData()
        },
        computed: {
            hasChanged() {
                if (!diff(this.section, this.originalData)) return false;

                return true;
            },
            loaded() {
                return this.loadStatus.sectionLoaded&&this.loadStatus.semesterLoaded
            }
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
                    title: "Warning, Danger Ahead!",
                    text: "This action will delete this section and its all associated data, i.e. student's attendances, enrollments, timetables. \n\nAre you sure want to proceed?",
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
            getSemesterData() {
                this.loadStatus.semesterLoaded = false
                axios.get('/api/semesters/options')
                .then(response => {
                    this.semesterOptions = response.data;
                    this.loadStatus.semesterLoaded = true
                })
                .catch(error => {
                    this.loadStatus.semesterLoaded = true
                    toastr.error(error.message, 'Opps! Something went wrong.')
                })
            }
        }
    }
</script>

<style scoped>

</style>
