<template>
    <div>
        <header-component>
            <template v-slot:title>Edit Semester</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item"><router-link :to="{name: 'semesters'}">Semesters</router-link></li>
                <li class="breadcrumb-item">Edit Semester</li>
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
                            <h3 class="card-title">Edit Semester</h3>
                        </div>

                        <div class="card-body">
                            <form action="" @submit.prevent="handleSubmit">
                                <div class="row">
                                    <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="academic_year">Academic Year</label>
                                        <select class="form-control" style="width: 100%" id="academic_year" v-model="academicYear">
                                            <option value="" disabled>Select academic year</option>
                                            <option v-for="(option, index) in yearOptions" :value="index">{{index}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="year">Year Name</label>
                                        <select :disabled="!academicYear" style="width: 100%;" id="year" class="form-control" v-model="semester.year_id">
                                            <option value="" disabled>Select year name</option>
                                            <option  v-for="(option, index) in yearOptions[academicYear]" :value="option.id">{{option.name}}</option>
                                        </select>
                                    </div>
                                </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="name">Semester Name</label>
                                            <input type="text" id="name" class="form-control" v-model="semester.semester_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date">Start Date</label>
                                            <input type="text" id="start_date" class="form-control" data-language='en' data-date-format="yyyy-mm-dd" v-model="semester.start_date" required autocomplete="off" placeholder="yyyy-mm-dd">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="end_date">End Date</label>
                                            <input type="text" id="end_date" class="form-control" data-language="en" data-date-format="yyyy-mm-dd" v-model="semester.end_date" required autocomplete="off" placeholder="yyyy-mm-dd">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button :disabled="!hasChanged" type="submit" class="btn bg-gradient-primary">Save</button></div>
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
        name: "SemestersManage",
        data() {
            return {
                id: null,
                semester: {},
                originalData: {},
                error: {
                    title: null,
                    details: {}
                },
                years: {},
                academicYear: "",
                selectedID: "",
                loadStatus: {
                    semesterLoaded: false,
                    yearLoaded: false
                }
            }
        },
        created() {
            this.id = this.$route.params.id
            axios.get('/api/semesters/' + this.id)
                .then(response => {
                    this.loadStatus.semesterLoaded = true
                    this.originalData = response.data;
                    this.semester = {...this.originalData}
                })
                .catch(error => {
                    this.loadStatus.semesterLoaded = true
                    this.error.title = this.error = error.response.data.message || error.message;
                })
            this.getYearData()
        },
        mounted() {
            let vm = this
            $('#start_date').datepicker({
                onSelect: function(formattedDate, date, inst) {
                    this.semester.start_date = formattedDate
                }.bind(this)
            })
            $('#end_date').datepicker({
                onSelect: function(formattedDate, date, inst) {
                    this.semester.end_date = formattedDate
                }.bind(this)
            })
        },
        computed: {
            hasChanged() {
                if (!diff(this.semester, this.originalData)) return false;

                return true;
            },
            yearOptions() {
                return _.groupBy(this.years, 'academic_year');
            },
            loaded() {
                return this.loadStatus.semesterLoaded&&this.loadStatus.yearLoaded
            }
        },
        watch: {
            loaded() {
                if(this.loaded) {
                    this.selectedID = _.findIndex(this.years, {id: this.semester.year_id})
                    this.academicYear = this.years[this.selectedID].academic_year
                }
            }
        },
        methods: {
            handleSubmit() {
                this.loadStatus.semesterLoaded = false
                axios.put('/api/semesters/' + this.id, this.semester)
                    .then(response => {
                        this.$router.back()
                        toastr.success('Edit Successful.', 'Success')
                    }).catch(error => {
                    this.loadStatus.semesterLoaded = true;
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                })
            },
            handleDelete() {
                this.loadStatus.semesterLoaded = false;
                axios.delete('/api/semesters/' + this.id)
                    .then(response => {
                        swal("Record has been deleted.", {
                            icon: "success",
                        });
                        this.$router.back()
                    })
                    .catch(error => {
                        this.loadStatus.semesterLoaded = true
                        this.error.title = error.response.data.message || error.message;
                    })
            },
            deleteConfirm() {
                swal({
                    title: "Warning, Danger Ahead!",
                    text: "This action will delete this semester and its all associated data, i.e. student's attendances, courses, sections. \n\nAre you sure want to proceed?",
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
            getYearData() {
                this.loadStatus.yearLoaded = false;
                axios.get('/api/years')
                .then(response => {
                    this.years = response.data;
                    this.loadStatus.yearLoaded = true
                })
                .catch(error => {
                    this.loadStatus.yearLoaded = true
                    console.log(error)
                    this.error = error.response.data.message || error.message;
                })
            },
        }
    }
</script>

<style scoped>

</style>
