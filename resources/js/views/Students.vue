<template>
    <div>
        <div class="loading text-center align-items-center justify-content-center d-flex vh-100" v-if="!loaded">
            <loader-component></loader-component>
        </div>
        <div class="error mt-4" v-if="error" >
            <div class="alert alert-danger" role="alert">
                {{error}}
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12" v-show="!error">
                <div class="alert alert-success alert-dismissible fade show" v-if="successMsg">
                    {{successMsg}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link active" id="nav-approved-tab" data-toggle="tab" href="#nav-approved" role="tab" aria-controls="nav-approved" aria-selected="true">Approved</a>
                        <a class="nav-item nav-link" id="nav-unapproved-tab" data-toggle="tab" href="#nav-unapproved" role="tab" aria-controls="nav-unapproved" aria-selected="false">Not yet approved</a>
                    </div>
                </nav>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-approved" role="tabpanel" aria-labelledby="nav-approved-tab">
                        <p v-if="!approvedStudents.length">There's nothing to show</p>
                        <table class="table table-striped" v-show="loaded&&!error&&approvedStudents.length">
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
                            <tr v-for="approvedStudent in approvedStudents" :key="approvedStudent.id">
                                <td>{{approvedStudent.id}}</td>
                                <td>{{approvedStudent.name}}</td>
                                <td>{{approvedStudent.email}}</td>
                                <td>{{approvedStudent.nrc}}</td>
                                <td>{{approvedStudent.father_name}}</td>
                                <td>{{approvedStudent.urn}}</td>
                                <td>{{approvedStudent.phone}}</td>
                                <td>
                                    <button class="btn btn-secondary">Manage</button>
                                    <button @click="disapprove(approvedStudent.id)" class="btn btn-danger">Disable</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="tab-pane fade" id="nav-unapproved" role="tabpanel" aria-labelledby="nav-unapproved-tab">
                        <p v-if="!unapprovedStudents.length">There's nothing to show</p>
                        <table class="table table-striped" v-show="loaded&&!error&&unapprovedStudents.length">
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
                            <tr v-for="unapprovedStudent in unapprovedStudents" :key="unapprovedStudent.id">
                                <td>{{unapprovedStudent.id}}</td>
                                <td>{{unapprovedStudent.name}}</td>
                                <td>{{unapprovedStudent.email}}</td>
                                <td>{{unapprovedStudent.nrc}}</td>
                                <td>{{unapprovedStudent.father_name}}</td>
                                <td>{{unapprovedStudent.urn}}</td>
                                <td>{{unapprovedStudent.phone}}</td>
                                <td>
                                    <button class="btn btn-secondary">Manage</button>
                                    <button @click="approve(unapprovedStudent.id)" class="btn btn-success">Approve</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        data() {
            return {
                approvedStudents: {},
                unapprovedStudents: {},
                error: null,
                loaded: false,
                successMsg: null
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
                    this.unapprovedStudents = response.data.not_approved;
                    this.loaded = true;
                })
                .catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                })
            },
            approve(id) {
                this.loaded = false;
                axios.post('/api/students/'+ id +'/approve')
                .then(response => {
                    this.getStudentData();
                    this.loaded = true;
                    this.successMsg = "Successfully enabled that account."
                })
                .catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                })
            },
            disapprove(id) {
                this.loaded = false;
                axios.post('/api/students/'+ id +'/disapprove')
                .then(response => {
                    this.getStudentData();
                    this.loaded = true;
                    this.successMsg = "Successfully disabled that account."
                })
                .catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                })
            }
        }
    }

</script>
