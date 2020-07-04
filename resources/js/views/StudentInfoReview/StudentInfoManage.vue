<template>
    <div>
        <header-component>
        <template v-slot:title>Student Info Change Requests</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item"><router-link :to="{name: 'student_info_review'}">Student Info Change Requests</router-link></li>
            <li class="breadcrumb-item">Review Request</li>
        </template>
    </header-component>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                    <div class="card-header">
                        <h3 class="card-title">Review Requests</h3>
                    </div>
                    <div class="card-body">
                        <div class="row border-bottom justify-content-between">
                            <div class="col-5">
                                <h5 class="text-center">Current Data</h5>
                            </div>
                            <div class="col-5">
                                <h5 class="text-center">Change Requested Data</h5>
                            </div>
                        </div>
                        <div v-for="(item, index) in student" class="row">
                            <div class="col-5">
                                <h5>{{index}}</h5>
                                <p>{{item.old}}</p>
                            </div>
                            <div class="col-2 mt-3">
                                <i class="fas fa-arrow-right"></i>
                            </div>
                            <div class="col-5">
                                <h5>{{index}}</h5>
                                <p>{{item.new}}</p>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button @click="approveRequest" class="btn btn-primary">Approve Request</button>
                        <button @click="declineRequest" class="btn btn-danger">Decline Request</button>
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
        name: "StudentInfoManage",
        props: ['auth'],
        data() {
            return {
                loaded: false,
                id: this.$route.params.id,
                student: []
            }
        },

        created() {
            this.getStudentReviewInfo()
        },
        methods: {
            getStudentReviewInfo() {
                this.loaded = false
                axios.get(`/api/student_info_review/${this.id}`)
                .then(({data}) => {
                    this.student = data
                    this.loaded = true
                })
                .catch(error => {
                    toastr.error(error.message, 'Error')
                })
            },
            approveRequest() {
                this.loaded = false
                axios.post(`/api/student_info_review/${this.id}/approve`)
                .then(() => {
                    this.$router.push({name: 'student_info_review'})
                    toastr.success('Student info updated successfully.', 'Success')
                })
                .catch((error) => {
                    toastr.error(error.message, 'Error')
                })
            },
            declineRequest() {
                this.loaded = false
                axios.delete(`/api/student_info_review/${this.id}`)
                .then(() => {
                    this.$router.push({name: 'student_info_review'})
                    toastr.success('Request declined successfully.', 'Success')
                })
                .catch((error) => {
                    toastr.error(error.message, 'Error')
                })
            }
        }
    }
</script>

<style scoped>

</style>
