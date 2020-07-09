<template>
    <div>
        <div class="card">
            <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
            <div class="card-header">
                <h3 class="card-title">Students enrolled in this section</h3>
                <div class="card-tools">
                    <button class="btn btn-sm bg-gradient-success mr-2" data-toggle="collapse" data-target="#enroll-student-input" aria-expanded="false" aria-controls="timetable-input"><i class="fas fa-plus mr-1"></i>Enroll student</button>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <div class="collapse" id="enroll-student-input">
                <form action="#">
                    <div class="row justify-content-center align-items-center m-3">
                        <div class="col-md-10">
                            <form action="#" @submit.prevent="handleEnroll">
                                <b>Select a student:</b>
                                <select style="width: 100%" class="form-control" id="select_student" :value="formData.student_id">
                                    <option class="default" value="" selected>Select student:</option>
                                </select>
                                <button type="submit" class="btn btn-primary mt-2">Enroll</button>
                            </form>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                <table id="student_table" class="table table-hover table-striped table-bordered" v-show="students.length">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th class="none">NRC</th>
                            <th class="none">Father Name</th>
                            <th class="none">URN</th>
                            <th class="min-desktop">Phone</th>
                            <th  class="min-desktop">Enrolled at</th>
                            <th data-priority="1">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(student, index) in students" :key="student.id">
                            <td>{{index+1}}</td>
                            <td>{{student.user.name}}</td>
                            <td>{{student.user.email}}</td>
                            <td>{{student.nrc}}</td>
                            <td>{{student.father_name}}</td>
                            <td>{{student.urn}}</td>
                            <td>{{student.phone}}</td>
                            <td>{{student.pivot.created_at | dateTimeFilter}}</td>
                            <td>
                                <button @click="handleUnregister(student.id)" class="btn btn-sm btn-danger">Unregister</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "SectionStudents",
        props: ['section'],
        data() {
            return {
                id: this.$route.params.id,
                dt: null,
                students: [],
                allStudents: [],
                loadStatus: {
                    studentsLoaded: false,
                    allStudentLoaded: false
                },
                formData: {
                    student_id: '',
                }
            }
        },
        mounted() {
            this.dt = this.tableLoad()
            let vm = this
            this.getStudentsData()
            this.getAllStudentsData()
            $(document).ready(function() {
                $('#select_student').select2().on('change', function (e) {
                    vm.formData.student_id = e.target.value
                });
            });
        },
        filters: {
            dateTimeFilter(value) {
                return moment(value).format('LLLL')
            },
        },
        computed: {
            loaded() {
                return this.loadStatus.allStudentLoaded&&this.loadStatus.studentsLoaded
            }
        },
        watch: {
            students(val) {
                this.dt.destroy();
                this.$nextTick(() => {
                    this.dt = this.tableLoad()
                })
            },
            allStudents(val) {
                $('#select_student').find('*').not('.default').remove();
                $('#select_student').select2({
                    data: this.allStudents
                }).css('width', '100%');

            }
        },
        methods: {
            getStudentsData() {
                this.loadStatus.studentsLoaded = false
                return axios.get(`/api/sections/${this.id}/get-students`)
                .then(({data}) => {
                    this.students = data
                    this.loadStatus.studentsLoaded = true
                })
                .catch(error => {
                    toastr.error(error.message, 'Error')
                })
            },
            getAllStudentsData() {
                this.loadStatus.allStudentLoaded = false
                this.allStudents = []
                return axios.get(`/api/sections/${this.id}/get-nStudents`)
                .then(response => {
                    this.loadStatus.allStudentLoaded = true
                    response.data.map(student => {
                        this.allStudents.push({id: student.id, text: `${student.name} (URN: ${student.urn})`})
                    })
                })
                .catch(error => {
                    toastr.error(this.error, 'Error')
                })
            },
            handleEnroll() {
                this.loadStatus.studentsLoaded = false;
                axios.post(`/api/sections/${this.id}/enroll`, this.formData)
                .then(() => {
                    this.getStudentsData()
                    this.getAllStudentsData();
                    this.formData.student_id = ''
                    toastr.success('Enrollment successful.', 'Success')
                })
                .catch(error => {
                    toastr.error(error.message, 'Error')
                })
            },
            handleUnregister(id) {
                this.loadStatus.studentsLoaded = false;
                axios.post(`/api/sections/${this.id}/unregister`, {student_id: id})
                .then(() => {
                    this.getStudentsData()
                    this.getAllStudentsData();
                    toastr.success('Unregister successful.', 'Success')
                })
                .catch(error => {
                    toastr.error(error.message, 'Error')
                })
            },
            tableLoad()
            {
                return $('#student_table').DataTable({
                dom: 'lBfrtip',
                "responsive": true,
                "autoWidth": false,
                "pageLength": 10,
                'destroy': true,
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
                        title: `StudentsIn${this.section.name}Export`,
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6]
                        },
                    },
                    {
                        extend: 'excelHtml5',
                        text: '<i class="far fa-file-excel mr-2"></i> Excel',
                        title: `StudentsIn${this.section.name}Export`,
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6]
                        },
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print mr-2"></i> Print',
                        title: `Student Enrolled in ${this.section.name}`,
                        exportOptions: {
                            columns: [ 0, 1, 2, 3, 4, 5, 6]
                        },
                    }
                ],
            });
            }
        }
    }
</script>

<style scoped>

</style>
