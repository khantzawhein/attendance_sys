<template>
    <div>
        <div class="loading text-center align-items-center justify-content-center d-flex vh-100" v-if="!loaded">
            <div class="spinner-border text-primary" role="status">
                <span class="sr-only">Loading...</span>
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
                        <p v-if="!approvedStudents">There's nothing to show</p>
                        <table class="table table-striped" v-show="loaded&&!error&&approvedStudents">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>URN</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr v-for="approvedStudent in approvedStudents">
                                <td>{{approvedStudent.id}}</td>
                                <td>{{approvedStudent.name}}</td>
                                <td>{{approvedStudent.email}}</td>
                                <td>{{approvedStudent.urn}}</td>
                                <td>
                                    <button class="btn btn-primary">Manage</button>
                                    <button @click="disapprove(approvedStudent.id)" class="btn btn-danger">Disable</button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab-pane fade" id="nav-unapproved" role="tabpanel" aria-labelledby="nav-unapproved-tab">
                        <p v-if="!unapprovedStudents">There's nothing to show</p>
                        <table class="table table-striped" v-show="loaded&&!error&&unapprovedStudents">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>URN</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            <tr v-for="unapprovedStudent in unapprovedStudents">
                                <td>{{unapprovedStudent.id}}</td>
                                <td>{{unapprovedStudent.name}}</td>
                                <td>{{unapprovedStudent.email}}</td>
                                <td>{{unapprovedStudent.urn}}</td>
                                <td>
                                    <button class="btn btn-primary">Manage</button>
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
                approvedStudents: null,
                unapprovedStudents: null,
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
                    this.$router.push({name: 'students'})
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
                    this.$router.push({name: 'students'})
                })
                .catch(error => {
                    this.loaded = true;
                    this.error = error.response.data.message || error.message;
                })
            }
        }
    }

</script>
