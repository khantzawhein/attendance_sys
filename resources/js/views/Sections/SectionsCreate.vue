<template>
    <div>
    <header-component>
        <template v-slot:title>Create a Class</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item"><router-link :to="{name: 'sections'}">Classes</router-link></li>
            <li class="breadcrumb-item active">Create Class</li>
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
                        <h3 class="card-title">Create Class</h3>
                    </div>
                    <div class="card-body">
                        <form action="" @submit.prevent="handleSubmit">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Class Name</label>
                                        <input type="text" id="name" class="form-control" v-model="formData.name" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Semester</label>
                                    <select-component class="w-100" :options="semesterOptions" v-model="formData.semester_id">
                                        <option value="" selected class="default">Select Semester:</option>
                                    </select-component>
                                </div>
                                <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="startTime">Class Start Time:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                </div>
                                                <input v-model="formData.start_time" type="text" class="form-control float-right" id="startTime" required>
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
                                                <input v-model="formData.end_time" type="text" class="form-control float-right" id="endTime" required>
                                            </div>
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
        name: "SectionsCreate",
        data() {
            return {
                formData: {
                    name: "",
                    semester_id: "",
                    start_time: "",
                    end_time: ""
                },
                error: {
                    title: null,
                    details: {}
                },
                semesterOptions: {},
                loaded: true
            }
        },
        created() {
            this.getSemesterData()
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
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.post('/api/sections', this.formData)
                .then(response => {
                    this.loaded = true
                    toastr.success('Section creation successful.', 'Success')
                    this.$router.push({name: 'sections'})
                })
                .catch(error => {
                    this.loaded = true
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                })
            },
            getSemesterData() {
                this.loaded = false
                axios.get('/api/semesters/options')
                .then(response => {
                    this.semesterOptions = response.data;
                    this.loaded = true
                })
                .catch(error => {
                    this.loaded = true
                    toastr.error(error.message, 'Opps! Something went wrong.')
                })
            }
        }
    }
</script>

<style scoped>

</style>
