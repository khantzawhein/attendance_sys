<template>
    <div>
        <header-component>
        <template v-slot:title>Student Lists</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item">Students</li>
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
                        <h3 class="card-title">Student Lists</h3>
                    </div>
                    <div class="card-body">
                        <p v-if="!approvedStudents.length">There's nothing to show</p>
                        <table class="table table-hover table-nowrap" v-show="approvedStudents.length">
                            <thead>
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>NRC</th>
                                    <th>Father Name</th>
                                    <th>URN</th>
                                    <th>Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(student, index) in approvedStudents" :key="student.id">
                                    <td>{{index+1}}</td>
                                    <td>{{student.name}}</td>
                                    <td>{{student.email}}</td>
                                    <td>{{student.nrc}}</td>
                                    <td>{{student.father_name}}</td>
                                    <td>{{student.urn}}</td>
                                    <td>{{student.phone}}</td>
                                    <td>
                                        <router-link :to="{name: 'students.manage', params: {id: student.id}}" class="btn btn-secondary">Manage</router-link>
                                        <button @click="disapprove(student.id)" class="btn btn-danger">Disable</button>
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
                approvedStudents: {},
                error: null,
                loaded: false,
            }
        },
        created() {
            this.getStudentData();
        },
        methods: {
            getStudentData() {
                axios.get('/api/students')
                .then(response => {
                    this.approvedStudents = response.data.approved;
                    this.loaded = true;
                })
                .catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                    toastr.error(this.error, 'Error')
                })
            },
            disapprove(id) {
                this.loaded = false;
                axios.post('/api/students/'+ id +'/disapprove')
                .then(response => {
                    this.getStudentData();
                    this.loaded = true;
                    toastr.success('Successfully disabled.', 'Success')
                })
                .catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                    toastr.error(this.error, 'Error')
                })
            }
        }
    }

</script>
