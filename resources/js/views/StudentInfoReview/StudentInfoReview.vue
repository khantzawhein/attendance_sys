<template>
    <div>
        <header-component>
        <template v-slot:title>Student Info Change Requests</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item">Student Info Change Requests</li>
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
                        <h3 class="card-title">Requests List</h3>
                    </div>
                    <div class="card-body">
                        <p class="text-center" v-if="!requests.length">There is no request yet.</p>
                        <table id="student_table" class="table table-hover table-striped table-bordered" v-show="requests.length">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Student Name</th>
                                    <th>Date of Request</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(request, index) in requests" :key="request.id">
                                    <td>{{index+1}}</td>
                                    <td>{{request.student.user.name}}</td>
                                    <td>{{request.created_at | dateTimeFilter}}</td>
                                    <td>
                                        <router-link v-if="auth>=2" :to="{name: 'student_info_review.manage', params: {id: request.id}}" class="btn btn-sm btn-secondary">Details</router-link>
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
        name: "StudentInfoReview",
        props: ['auth'],
        data() {
            return {
                loaded: false,
                requests: []
            }
        },
        created() {
            this.getRequestData()
        },
        filters: {
            dateTimeFilter(value) {
                return moment(value).format('LLLL')
            },
        },
        methods: {
            getRequestData() {
                this.loaded = false
                axios.get('/api/student_info_review')
                .then(({data}) => {
                    this.requests = data
                    this.loaded = true
                })
                .catch(error => {
                    toastr.error(error.message, 'Error')
                })
            }
        }
    }
</script>

<style scoped>

</style>
