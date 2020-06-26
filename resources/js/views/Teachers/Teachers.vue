<template>
    <div>
    <header-component>
        <template v-slot:title>Teachers</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item">Teachers</li>
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
                        <h3 class="card-title">Teacher Lists</h3>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="$router.push({name: 'teachers.create'})"><i class="fas fa-plus mr-1"></i> Teachers</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p v-if="!teachers.length">There's nothing to show</p>
                        <table id="teacher_table" class="table table-hover table-striped" v-show="teachers.length">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Department</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(teacher, index) in teachers" :key="teacher.id">
                                    <td>{{index+1}}</td>
                                    <td>{{teacher.name}}</td>
                                    <td>{{teacher.email}}</td>
                                    <td>{{teacher.role}}</td>
                                    <td>{{teacher.department}}</td>
                                    <td>
                                        <router-link :to="{name: 'teachers.manage', params: {id: teacher.id}}" class="btn btn-secondary">Manage</router-link>
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
</template>
<script>
    export default {
        data() {
            return {
                teachers: {},
                error: null,
                loaded: false
            }
        },
        created() {
            this.getTeacherData()
            .then(() => {
                this.tableLoad()
            });
        },
        methods: {
            getTeacherData() {
                return axios.get('/api/teachers')
                .then(response => {
                    this.teachers = response.data;
                    this.loaded = true;
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
                        $('#teacher_table').DataTable({
                        dom: 'lBfrtip',
                        "responsive": true,
                        "autoWidth": false,
                        "pageLength": 10,
                        buttons: [
                            {
                                extend: 'copyHtml5',
                                text: '<i class="far fa-clipboard mr-2"></i>Copy',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4]
                                },


                            },
                            {
                                extend: 'csvHtml5',
                                text: '<i class="fas fa-file-csv mr-2"></i>CSV',
                                title: 'TeachersExport',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4]
                                },

                            },
                            {
                                extend: 'excelHtml5',
                                text: '<i class="far fa-file-excel mr-2"></i> Excel',
                                title: 'TeachersExport',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4]
                                },

                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print mr-2"></i> Print',
                                title: 'Teacher Lists',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4]
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
