<template>
    <div>
    <header-component>
    <template v-slot:title>All Courses</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
        <li class="breadcrumb-item">All Courses</li>
        </template>
    </header-component>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="error mt-4" v-if="error" >
                    <div class="alert alert-danger" role="alert">
                        <strong>{{error}}</strong>
                    </div>
                </div>
                <div class="card">
                <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                    <div class="card-header">
                        <h3 class="card-title">Course List</h3>
                        <div class="card-tools">
                            <button v-if="auth>=2" class="btn btn-success" @click="$router.push('/app/courses/create')"><i class="fas fa-plus mr-1"></i> Courses</button>
                        </div>
                    </div>
                    <div class="card-body ">
                        <table id="course_table" class="table table-hover table-bordered table-striped" v-show="courses.length">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Module No</th>
                                    <th>Module Name</th>
                                    <th class="min-desktop">Teacher Name</th>
                                    <th class="none">Academic Year</th>
                                    <th class="min-tablet">Year</th>
                                    <th class="none">Semester</th>
                                    <th data-priority="1" v-if="auth>=2">Action</th>
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
                                            <router-link :to="{name: 'courses.attendances', params: {id: course.id}}" class="btn btn-sm btn-success">Attendances</router-link>
                                            <router-link :to="{name: 'courses.manage', params: {id: course.id}}" class="btn btn-sm btn-secondary">Manage</router-link>
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
        name: "CoursesAll",
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
        mounted() {

        },
        methods: {
            getCoursesData() {
                this.loaded = false;
                axios.get('/api/courses/all')
                .then(response => {
                    this.courses = response.data.data;
                    this.loaded = true;
                    this.tableLoad()
                })
                .catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                })
            },
            tableLoad()
            {
                $(document).ready(
                    function() {
                        $('#course_table').DataTable({
                        dom: 'lBfrtip',
                        "responsive": true,
                        "autoWidth": false,
                        "pageLength": 10,
                        buttons: [
                            {
                                extend: 'copyHtml5',
                                text: '<i class="far fa-clipboard mr-2"></i>Copy',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                                },


                            },
                            {
                                extend: 'csvHtml5',
                                text: '<i class="fas fa-file-csv mr-2"></i>CSV',
                                title: 'CoursesExport',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                                },

                            },
                            {
                                extend: 'excelHtml5',
                                text: '<i class="far fa-file-excel mr-2"></i> Excel',
                                title: 'CoursesExport',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                                },

                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print mr-2"></i> Print',
                                title: 'Course Lists',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                                },

                            }
                        ],
                        });
                    }
                )
            }
        }
    }
</script>

<style scoped>

</style>
