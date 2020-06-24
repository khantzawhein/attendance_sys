<template>
    <div>
    <header-component>
        <template v-slot:title>Courses</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item">Courses</li>
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
                <div class="error mt-4" v-if="error" >
                    <div class="alert alert-danger" role="alert">
                        <strong>{{error}}</strong>
                    </div>
                </div>
                <div class="card" v-show="loaded&&!error">
                    <div class="card-header">
                        <h3 class="card-title">Course List</h3>
                        <div class="card-tools">
                            <button v-if="auth>=2" class="btn btn-success" @click="$router.push('/app/courses/create')"><i class="fas fa-plus mr-1"></i> Courses</button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <p v-if="!courses.length">There's nothing to show</p>
                        <table class="table table-hover table-nowrap" v-if="courses.length">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Module No</th>
                            <th>Module Name</th>
                            <th>Teacher Name</th>
                            <th>Academic Year</th>
                            <th>Year</th>
                            <th>Semester</th>
                            <th v-if="auth>=2">Action</th>
                        </tr>
                    </thead>
                        <tbody>
                            <tr v-for="(course, index) in courses" :key="course.id">
                                <td>{{index+1}}</td>
                                <td>{{course.module_no}}</td>
                                <td>{{course.module_name}}</td>
                                <td>{{course.teacher_name}}</td>
                                <td>{{course.academic_year}}</td>
                                <td>{{course.year}}</td>
                                <td>{{course.semester}}</td>
                                <td v-if="auth>=2">
                                    <router-link :to="{name: 'courses.manage', params: {id: course.id}}" class="btn btn-secondary">Manage</router-link>
                                </td>
                            </tr>
                        </tbody>
                </table>
                    </div>
                </div>
                <!-- Modal -->
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
        name: "Course",
        props: ['auth'],
        data() {
            return {
                courses: {},
                error: null,
                loaded: false,
                accessCode: null
            }
        },
        created() {
            this.getCoursesData();
        },
        methods: {
            getCoursesData() {
                this.loaded = false;
                axios.get('/api/courses')
                .then(response => {
                    this.courses = response.data.data;
                    this.loaded = true;
                })
                .catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                })
            },

        }
    }
</script>

<style scoped>

</style>
