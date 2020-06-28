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
                            <p v-if="!attendances.length">There's nothing to show</p>
                            <table id="my_attendance_table" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Module No</th>
                                        <th>Module Name</th>
                                        <th>Day</th>
                                        <th>Week</th>
                                        <th>Time</th>
                                        <th data-priority="2">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(attendance, index) in attendances">
                                        <td>{{index+1}}</td>
                                        <td>{{attendance.module_no}}</td>
                                        <td>{{attendance.module_name}}</td>
                                        <td>{{attendance.day|day}}</td>
                                        <td>{{attendance.week}}</td>
                                        <td>{{attendance.created_at|dateTimeFilter}}</td>
                                        <td><span style="font-size: 1rem" :class="['badge', statusColor(attendance.status)]">{{attendance.description}}</span></td>
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
            this.getAttendanceData()
            .then(() => {
                this.tableLoad()
            })
        },
        filters: {
            dateTimeFilter(value) {
                return moment(value).format('LLLL')
            },
            day(value) {
                let day = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                return day[value]
            }
        },
        methods: {
            getAttendanceData() {
                return axios.get('/api/my-attendances')
                .then(({data}) => {
                    this.attendances = data;
                    this.student_name = this.attendances[0].student_name
                    this.loaded = true
                })
                .catch((error) => {
                    toastr.error(error.message, 'Error')
                })
            },
            statusColor(status)
            {
                if(status == 1) {
                    return 'badge-success'
                }
                else if (status == 0) {
                    return 'badge-danger'
                }
                else if (status == 2) {
                    return 'badge-warning'
                }
                else if (status == 3) {
                    return 'badge-info'
                }
            },
            tableLoad()
            {
                $(document).ready(() => {
                        $('#my_attendance_table').DataTable({
                        dom: 'lBfrtip',
                        "responsive": true,
                        "autoWidth": false,
                        "pageLength": 10,
                        "order": [[ 0, "desc" ]],

                        buttons: [
                            {
                                extend: 'copyHtml5',
                                text: '<i class="far fa-clipboard mr-2"></i>Copy',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6]
                                },


                            },
                            {
                                extend: 'csvHtml5',
                                text: '<i class="fas fa-file-csv mr-2"></i>CSV',
                                title: `${this.student_name} Attendances`,
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6]
                                },

                            },
                            {
                                extend: 'excelHtml5',
                                text: '<i class="far fa-file-excel mr-2"></i> Excel',
                                title: `${this.student_name} Attendances`,
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6]
                                },

                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print mr-2"></i> Print',
                                title: `${this.student_name}'s Attendances`,
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5, 6]
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
