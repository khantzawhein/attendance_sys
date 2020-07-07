<template>
    <div>
    <header-component>
        <template v-slot:title>Create a Course</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item"><router-link :to="{name: 'courses'}">Courses</router-link></li>
            <li class="breadcrumb-item active">Create Course</li>
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
                <div class="card card-default" v-if="loaded">
                    <div class="card-header">
                        <h3 class="card-title">Create Course</h3>
                    </div>
                    <div class="card-body">
                        <form action="" @submit.prevent="handleSubmit">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="module_no">Module No</label>
                                        <input v-model="formData.module_no" type="text" class="form-control" id="module_no" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="module_no">Module Name</label>
                                        <input v-model="formData.module_name" type="text" class="form-control" id="module_name" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Teacher</label>
                                        <select-component class="w-100" :options="teachers" v-model="formData.teacher_id">
                                            <option value="" selected class="default">Select Teacher:</option>
                                        </select-component>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Semester</label>
                                        <select-component class="w-100" :options="semesters" v-model="formData.semester_id">
                                            <option value="" selected class="default">Select Semester:</option>
                                        </select-component>
                                    </div>
                                </div>
                                <div class="col-md-6">
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
  <!-- /.content-wrapper -->
</template>

<script>
    export default {
        name: "CoursesCreate",
        props: ['auth'],
        data() {
            return {
                teachers: {},
                semesters: {},
                selected: null,

                formData: {
                    module_no: "",
                    module_name: "",
                    teacher_id: "",
                    semester_id: ""
                },
                error: {
                    title: null,
                    details: {}
                },
                loadStatus: {
                    semesters: false,
                    teachers: false,
                    course: true
                }
            }
        },
        computed: {
            loaded() {
                return this.loadStatus.semesters&&this.loadStatus.teachers&&this.loadStatus.course
            }
        },
        created() {
            this.getTeacherData();
            this.getSemesterData();
        },
        methods: {
            getTeacherData() {
                this.loadStatus.teachers = false;
                axios.get('/api/teachers')
                .then(response => {
                    let data = []
                    response.data.map(teacher => {
                        data.push({id: teacher.id, text: teacher.name})
                    })
                    this.teachers = data;
                    if (this.auth == 2) {
                        this.formData.teacher_id = this.teachers[0].id
                    }
                    this.loadStatus.teachers = true;

                })
                .catch(error => {
                    this.loadStatus.teachers = true;
                    this.error.title = error.response.data.message || error.message;
                })
            },
            getSemesterData() {
                this.loadStatus.semesters = false;
                axios.get('/api/semesters/options')
                .then(response => {
                    this.semesters = response.data;
                    this.loadStatus.semesters = true;
                })
                .catch(error => {
                    this.loadStatus.semesters = true;
                    this.error.title = error.response.data.message || error.message;
                })
            },
            handleSubmit() {
                this.loadStatus.course = false
                axios.post('/api/courses', this.formData)
                .then(response => {
                    this.loadStatus.course = true
                    toastr.success('Course has been created.', 'Success')
                    this.$router.push({name: "courses"})
                })
                .catch(error => {
                    this.loadStatus.course = true
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                    toastr.error("Validation Failed", 'Error')
                })
            }
        }
    }
</script>

<style scoped>

</style>
