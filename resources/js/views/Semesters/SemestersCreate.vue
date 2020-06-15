<template>
    <div>
    <header-component>
        <template v-slot:title>Create a Semester</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item"><router-link :to="{name: 'semesters'}">Semesters</router-link></li>
            <li class="breadcrumb-item active">Create Semester</li>
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
                        <h3 class="card-title">Create Semester</h3>
                    </div>
                    <div class="card-body">
                        <form action="" @submit.prevent="handleSubmit">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="academic_year">Academic Year</label>
                                        <select class="form-control" style="width: 100%" id="academic_year">
                                            <option value="" selected disabled>Select academic year</option>
                                            <option v-for="(option, index) in yearOptions" :value="index">{{index}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="year">Year Name</label>
                                        <select :disabled="!academicYear" style="width: 100%;" id="year" class="form-control" v-model="formData.year_id">
                                            <option value="" selected disabled>Select year name</option>
                                            <option  v-for="(option, index) in yearOptions[academicYear]" :value="option.id">{{option.name}}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Semester Name</label>
                                        <input type="text" id="name" class="form-control" v-model="formData.semester_name" required placeholder="eg. First Semester">
                                    </div>
                                </div>


                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="text" id="start_date" class="form-control" data-language='en' data-date-format="yyyy-mm-dd" v-model="formData.start_date" required autocomplete="off" placeholder="yyyy-mm-dd">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="text" id="end_date" class="form-control" data-language="en" data-date-format="yyyy-mm-dd" v-model="formData.end_date" required autocomplete="off" placeholder="yyyy-mm-dd">
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
        name: "SemestersCreate",
        data() {
            return {
                formData: {
                    name: "",
                    year_id: "",
                    start_date: "",
                    end_date: ""
                },
                academicYear: "",
                years: {},
                error: {
                    title: null,
                    details: {}
                },
                loaded: false
            }
        },
        mounted() {
            let vm = this
            this.loaded = true;
            $('#start_date').datepicker({
                onSelect: function(formattedDate, date, inst) {
                    this.formData.start_date = formattedDate
                }.bind(this)
            })
            $('#end_date').datepicker({
                onSelect: function(formattedDate, date, inst) {
                    this.formData.end_date = formattedDate
                }.bind(this)
            })
            this.getYearData()
            $(document).ready(function() {
                $('#academic_year').select2().on('change', function (e) {
                    vm.academicYear = e.target.value
                });
            });
        },
        computed: {
            yearOptions() {
                return _.groupBy(this.years, 'academic_year');
            }
        },
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.post('/api/semesters', this.formData)
                .then(response => {
                    this.loaded = true
                    toastr.success('Semester creation successful.', 'Success')
                    this.$router.push({name: 'semesters'})
                })
                .catch(error => {
                    this.loaded = true
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                })
            },
            getYearData() {
                this.loaded = false;
                axios.get('/api/years')
                .then(response => {
                    this.years = response.data;
                    this.loaded = true
                })
                .catch(error => {
                    this.loaded = true
                    this.error = error.response.data.message || error.message;
                })
            },

        }
    }
</script>

<style scoped>

</style>
