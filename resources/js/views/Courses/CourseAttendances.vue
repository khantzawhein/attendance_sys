<template>
    <div>
    <header-component>
        <template v-slot:title>Course's Attendances</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item"><router-link :to="{name: 'courses'}">Courses</router-link></li>
            <li class="breadcrumb-item">Course's Attendances</li>
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
                        <h3 v-if="loaded" class="card-title">{{course.module_name}}'s Attendances</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                      <table id="attendance_table" class="table table-bordered table-striped">
                          <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Day</th>
                                <th>Weeks (from semester start)</th>
                                <th>DateTime</th>
                                <th data-priority="2">Status</th>
                                <th data-priority="1">Mark as</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-for="(attendance, index) in attendances">
                                <td>{{index+1}}</td>
                                <td>{{attendance.student_name}}</td>
                                <td>{{attendance.day|day}}</td>
                                <td>{{attendance.week}}</td>
                                <td>{{attendance.date|dateTimeFilter}}</td>
                                <td><span style="font-size: 1rem" :class="['badge', statusColor(attendance.status)]">{{attendance.description}}</span></td>
                                <td>
                                    <select class="form-control" id="markAs" @change="updateStatus(attendance.id,$event)">
                                        <option value="" selected disabled>Select: </option>
                                        <option value="1">Present</option>
                                        <option value="0">Absent</option>
                                        <option value="2">Late</option>
                                        <option value="3">Leave</option>
                                    </select>
                                </td>
                            </tr>
                          </tbody>
                      </table>
                    </div>
                    <!-- /.card-body -->
                  </div>
          <!-- /.card -->
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
    import moment from 'moment';
    export default {
        name: "CourseAttendances",
        props: ['auth'],
        data() {
            return {
                id: null,
                loadStatus: {
                    attendance: false,
                    course: false
                },
                attendances: [],
                course: {},
                module_no: this.$route.params.module_no,
                module_name: this.$route.params.module_name
            }
        },
        created() {
            this.id = this.$route.params.id
            this.getCourseData()
            this.getCourseAttendanceData()
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
        computed: {
            loaded() {
                return this.loadStatus.attendance&&this.loadStatus.course;
            }
        },
        methods: {
            getCourseAttendanceData() {
                this.loadStatus.attendance = false;
                return axios.get('/api/courses/'+ this.id + '/attendances')
                .then(({data}) => {
                    this.loadStatus.attendance = true
                    this.attendances = data
                 })
            },
            getCourseData() {
                this.loadStatus.course = false
                axios.get('/api/courses/' + this.id)
                    .then(response => {
                        this.course = response.data.data;
                        this.loadStatus.course = true
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
            updateStatus(id, event) {
                this.loaded = false
                axios.post(`/api/courses/${this.id}/attendances/${id}/update-status`, {
                    status: event.target.value
                })
                .then(() => {
                    this.getCourseAttendanceData();
                    event.target.value = ""
                    toastr.success('Status changed successfully', 'Success');
                })
                .catch((error) => {
                    toastr.error(error.message, 'Error')
                })
            },
            tableLoad()
            {
                $(document).ready(() => {
                        $('#attendance_table').DataTable({
                        dom: 'lBfrtip',
                        "responsive": true,
                        "autoWidth": false,
                        "pageLength": 10,
                        "columnDefs": [
                            { "width": "20%", "targets": 6 }
                        ],
                        "order": [[ 0, "desc" ]],

                        buttons: [
                            {
                                extend: 'copyHtml5',
                                text: '<i class="far fa-clipboard mr-2"></i>Copy',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5]
                                },


                            },
                            {
                                extend: 'csvHtml5',
                                text: '<i class="fas fa-file-csv mr-2"></i>CSV',
                                title: `${this.course.module_no} Attendances`,
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5]
                                },

                            },
                            {
                                extend: 'excelHtml5',
                                text: '<i class="far fa-file-excel mr-2"></i> Excel',
                                title: `${this.course.module_no} Attendances`,
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5]
                                },

                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print mr-2"></i> Print',
                                title: `${this.course.module_no}'s Attendances`,
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5]
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
