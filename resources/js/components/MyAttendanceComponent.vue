<template>
    <div>
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
</template>

<script>
    export default {
        name: "MyAttendanceComponent",
        data() {
            return {
                attendances: [],
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
                    this.$emit('loaded');
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
