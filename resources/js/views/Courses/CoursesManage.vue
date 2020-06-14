<template>
    <div>
        <header-component>
            <template v-slot:title>Edit Course</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item"><router-link :to="{name: 'courses'}">Courses</router-link></li>
                <li class="breadcrumb-item">Edit Courses</li>
            </template>
        </header-component>
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
<!--                    card header-->
                    <div class="loading text-center align-items-center justify-content-center d-flex vh-100" v-if="!loaded">
                        <loader-component></loader-component>
                    </div>
                    <div class="card card-default" v-if="loaded">
                        <div class="card-header">
                            <h3 class="card-title">Edit Course</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                                  <i class="fas fa-minus"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            <error-component :error="error"></error-component>
                            <form action="" @submit.prevent="handleSubmit">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="module_no">Module No</label>
                                            <input v-model="course.module_no" type="text" class="form-control" id="module_no" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="module_no">Module Name</label>
                                            <input v-model="course.module_name" type="text" class="form-control" id="module_name" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Teacher</label>
                                            <select-component class="w-100" :options="teachers" v-model="course.teacher_id">
                                                <option value="0" selected class="default">Select Teacher:</option>
                                            </select-component>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Term</label>
                                            <select-component class="w-100" :options="terms" v-model="course.term_id">
                                                <option value="0" selected class="default">Select Term:</option>
                                            </select-component>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button :disabled="!hasChanged"  type="submit" class="btn bg-gradient-primary">Save</button>
                                            </div>
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
    import {diff} from 'deep-diff/dist/deep-diff.min.js';

    export default {
        name: "CoursesManage",
        data() {
            return {
                teachers: [],
                terms: [],
                id: null,
                course: {},
                originalData: {},
                error: {
                    title: null,
                    details: {}
                },
                loadStatus: {
                    course: false,
                    terms: false,
                    teachers: false
                }
            }
        },
        created() {
            this.id = this.$route.params.id
            this.getCourseData();
            this.getTermData();
            this.getTeacherData();
        },
        computed: {
            hasChanged() {
                if (!diff(this.course, this.originalData)) return false;

                return true;
            },
            loaded() {
                return this.loadStatus.terms&&this.loadStatus.teachers&&this.loadStatus.course
            }
        },
        methods: {
            getCourseData() {
                this.loadStatus.course = false
                axios.get('/api/courses/' + this.id)
                .then(response => {
                    this.originalData = response.data.data;
                    this.originalData.term_id = this.originalData.term_id.toString();
                    this.originalData.teacher_id = this.originalData.teacher_id.toString();
                    this.course = {...this.originalData}


                    this.loadStatus.course = true
                })
                .catch(error => {
                    this.loadStatus.course = true
                    this.error.title = this.error = error.response.data.message || error.message;
                })
            },
            getTeacherData() {
                this.loadStatus.teachers = false
                axios.get('/api/teachers')
                .then(response => {
                    let data = []
                    response.data.map(teacher => {
                        data.push({id: teacher.id, text: teacher.name})
                    })
                    this.teachers = data;
                    this.loadStatus.teachers = true
                })
                .catch(error => {
                    this.loadStatus.teachers = false
                    this.error.title = error.response.data.message || error.message;
                })
            },
            getTermData() {
                this.loadStatus.terms = false
                axios.get('/api/terms')
                .then(response => {
                    let data = []
                    response.data.data.map(term => {
                        data.push({id: term.id, text: term.name})
                    })
                    this.terms = data;
                    this.loadStatus.terms = true
                })
                .catch(error => {
                    this.error.title = error.response.data.message || error.message;
                })
            },
            handleSubmit() {
                this.loadStatus.course = false
                axios.put('/api/courses/' + this.id, this.course)
                    .then(response => {
                        this.$router.back()
                        toastr.success('Edit Successful.', 'Success')
                    }).catch(error => {
                    this.loadStatus.course = true
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                })
            },
            handleDelete() {
                this.loadStatus.course = false
                axios.delete('/api/courses/' + this.id)
                    .then(response => {
                        swal("Record has been deleted.", {
                            icon: "success",
                        });
                        this.$router.back()
                    })
                    .catch(error => {
                        this.loadStatus.course = true
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
