<template>
    <div>
        <header-component>
            <template v-slot:title>My Courses</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item">My Courses</li>
            </template>
        </header-component>
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <error-component :error="error"></error-component>
                    <div class="card card-default">
                        <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                        <div class="card-header">
                            <h3 class="card-title">My Courses</h3>
                            <div class="card-tools">
                                <button  v-if="auth==1" class="btn btn-sm bg-gradient-success" data-toggle="collapse" data-target="#collapse_component" aria-expanded="false" aria-controls="collapse_component"><i class="fas fa-plus mr-1"></i>Add course</button>
                            </div>
                        </div>
                        <collapse-component>
                            <template v-slot:body>
                                <div class="col-md-12 mt-2">
                                    <form action="#">
                                        <div class="row justify-content-center">
                                            <div class="col-4">
                                                <div class="form-group">
                                                    <label for="access_code">Course Access Code:</label>
                                                    <input v-model="formData.access_code" type="text" class="form-control" id="access_code">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </template>
                        </collapse-component>
                        <div class="card-body">
                            <p v-if="my_courses.length==0">There's nothing to show</p>
                                <div class="accordion" id="days_slide" v-for="(data, index) in my_courses">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" :data-target="`#course${data[0].course_id}`" aria-expanded="true" :aria-controls="`#course${data[0].course_id}`">
                                                {{index}}
                                            </button>
                                        </h2>
                                    </div>

                                    <div :id="`course${data[0].course_id}`" class="collapse" aria-labelledby="headingOne" data-parent="#days_slide">
                                        <div class="card-body">
                                            <table class="table table-hover table-nowrap" >
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Module No.</th>
                                                        <th>Module Name</th>
                                                        <th>Academic Year</th>
                                                        <th>Semester</th>
                                                        <th>Teacher Name</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(my_course, index) in data">
                                                        <td>{{index+1}}</td>
                                                        <td>{{my_course.module_no}}</td>
                                                        <td>{{my_course.module_name}}</td>
                                                        <td>{{my_course.academic_year}}</td>
                                                        <td>{{my_course.semester}}</td>
                                                        <td>{{my_course.teacher_name}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal -->
                </div>
            </div>
            <!-- /.row -->
          </div><!-- /.container-fluid -->
        </div>
    </div>
</template>

<script>
    export default {
        name: "MyCourse",
        props: ['auth'],
        data() {
            return {
                formData: {
                    access_code: ""
                },
                loaded: false,
                my_courses: {},
                error: {
                    title: null,
                    details: {}
                },
            }
        },
        created() {
            this.getMyCourseData()
        },
        mounted() {
            Bus.$on('collapse_submit', () => {
                this.loaded = false;
                axios.post('/api/my_courses', this.formData)
                .then(() => {
                    this.getMyCourseData()
                    this.formData.access_code = ""
                    toastr.success('Course has been binded to your account.', 'Success')
                })
                .catch(() => {
                    this.loaded = true;
                    toastr.error('Incorrect access code', 'Sorry');
                })
            })
        },
        methods: {
            getMyCourseData() {
                 axios.get('/api/my_courses')
                    .then(({data}) => {
                        this.my_courses = _.groupBy(data.data, 'year')
                        this.loaded = true
                    })
                    .catch(error => {
                        this.loaded = true
                        toastr.error(error.message, 'Error')
                    })
            }
        }
    }
</script>

<style scoped>

</style>
