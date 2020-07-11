<template>
<div>
        <header-component>
            <template v-slot:title>My Attendances</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item">My Attendances</li>
            </template>
        </header-component>
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card card-default">
                        <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                        <div class="card-header">
                            <h3 class="card-title">{{student_name}}'s Attendances</h3>
                        </div>
                        <div class="card-body">
                            <my-attendance-component :student_name="student_name" @loaded="loaded=true"></my-attendance-component>
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
        name: "MyAttendances",
        data() {
            return {
                attendances: [],
                loaded: false,
                student_name: ""
            }
        },
        mounted() {
            this.getName()
        },
        methods: {
            getName() {
                axios.get('/api/user/name')
                .then(({data}) => {
                    this.student_name = data
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
