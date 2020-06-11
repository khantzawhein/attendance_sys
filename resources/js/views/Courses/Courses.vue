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
                            <button class="btn btn-success" @click="$router.push('/app/courses/create')"><i class="fas fa-plus mr-1"></i> Courses</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p v-if="!courses.length">There's nothing to show</p>
                        <table class="table table-hover table-nowrap" v-if="courses.length">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Module No</th>
                            <th>Module Name</th>
                            <th>Teacher Name</th>
                            <th>Term</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                        <tbody>
                            <tr v-for="(course, index) in courses" :key="course.id">
                                <td>{{index+1}}</td>
                                <td>{{course.module_no}}</td>
                                <td>{{course.module_name}}</td>
                                <td>{{course.teacher_name}}</td>
                                <td>{{course.term}}</td>
                                <td>
                                    <router-link :to="{name: 'courses.manage', params: {id: course.id}}" class="btn btn-secondary">Manage</router-link>
                                    <button @click="getAccessCode(course.id)" class="btn btn-primary" data-toggle="modal" :data-target="'#modal'+course.id">Access Code</button>
                                    <!-- model -->
                                    <div class="modal fade" :id="'modal'+course.id" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">View Access Code</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            <div class="modal-body">
                                                <div v-if="!accessCodeLoaded" class="text-center align-items-center justify-content-center d-flex">
                                                    <loader-component></loader-component>
                                                </div>
                                                <h1 class="text-center" v-if="accessCodeLoaded">{{accessCode}}</h1>
                                                <p class="text-center" v-if="accessCodeLoaded">Enter this code in the student portal</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button @click="resetAccessCode(course.id)" class="btn btn-danger">Reset Access Code</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <!-- model end-->
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
        data() {
            return {
                courses: {},
                error: null,
                loaded: false,
                accessCodeLoaded: false,
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
            getAccessCode(id) {
                this.accessCodeLoaded = false;
                axios.get('/api/courses/'+ id + '/get-code')
                .then(response => {
                    this.accessCode = response.data.access_code;
                    this.accessCodeLoaded = true;
                })
                .catch(error => {
                    this.accessCodeLoaded = true;
                    this.error = error.response.data.message || error.message;
                })
            },
            resetAccessCode(id) {
                this.accessCodeLoaded = false;
                axios.post('/api/courses/' + id + '/reset-code')
                .then(response => {
                    this.getAccessCode(id);
                })
                .catch(error => {
                    this.accessCodeLoaded = true;
                    this.error = error.response.data.message || error.message;
                })
            },
        }
    }
</script>

<style scoped>

</style>
