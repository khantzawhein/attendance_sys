<template>
    <div>
        <header-component>
        <template v-slot:title>Class Lists</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item">Classes</li>
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
                <error-component :error="error"></error-component>
                <div class="card" v-show="loaded&&!error.title">
                    <div class="card-header">
                        <h3 class="card-title">Class Lists</h3>
                        <div class="card-tools">
                            <button v-if="auth==3" class="btn btn-success" @click="$router.push({name: 'sections.create'})"><i class="fas fa-plus mr-1"></i> Class</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p v-if="!sections.length">There's nothing to show</p>
                        <table id="section_table" class="table table-hover table-bordered" v-show="sections.length">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Class Name</th>
                                    <th>Academic Year</th>
                                    <th>Year</th>
                                    <th>Semester</th>
                                    <th>Class Start At</th>
                                    <th>Class End At</th>
                                    <th data-priority="1">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(section, index) in sections" :key="section.id">
                                    <td>{{index+1}}</td>
                                    <td>{{section.name}}</td>
                                    <td>{{section.academic_year}}</td>
                                    <td>{{section.year}}</td>
                                    <td>{{section.semester}}</td>
                                    <td>{{section.start_time}}</td>
                                    <td>{{section.end_time}}</td>
                                    <td>
                                        <router-link :to="{name: 'sections.manage', params: {id: section.id}}" class="btn btn-secondary">Manage</router-link>
                                        <button @click="getAccessCode(section.id)" class="btn btn-primary" data-toggle="modal" :data-target="'#modal'+section.id">Access Code</button>
                                    <!-- model -->
                                    <div class="modal fade" :id="'modal'+section.id" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">View Access Code</h5>
                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                            <div class="modal-body">
                                                <div v-if="!accessCodeLoaded" class="text-center align-items-center justify-content-center d-flex">
                                                    <loader-component></loader-component>
                                                </div>
                                                <h1 class="text-center" v-if="accessCodeLoaded">{{accessCode}}</h1>
                                                <p class="text-center" v-if="accessCodeLoaded">Enter this code in the student portal</p>
                                            </div>
                                            <div class="modal-footer">
                                                <button @click="resetAccessCode(section.id)" class="btn btn-danger">Reset Access Code</button>
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                    <!-- model end-->
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
        name: "Sections",
        props: ['auth'],
        data() {
            return {
                sections: {},
                error: {
                    title: "",
                    details: ""
                },
                loaded: false,
                accessCodeLoaded: false,
            }
        },
        created() {
            this.getSectionsData()
            .then(() => this.tableLoad());
        },
        methods: {
            getSectionsData() {
                return axios.get('/api/sections')
                    .then(response => {
                        this.sections = response.data.data;
                        this.loaded = true;
                    })
                    .catch(error => {
                        this.loaded = true;
                        this.error.title = error.response.data.message || error.message;
                        this.error.details = error.response.data.errors;
                        toastr.error(this.error, 'Error')
                    })
            },
            getAccessCode(id) {
                this.accessCodeLoaded = false;
                axios.get('/api/sections/'+ id + '/get-code')
                .then(response => {
                    this.accessCode = response.data.access_code;
                    this.accessCodeLoaded = true;
                })
                .catch(error => {
                    this.accessCodeLoaded = true;
                    this.error = error.response.data.message || error.message;
                })
            },
            resetAccessCode(id) {
                this.accessCodeLoaded = false;
                axios.post('/api/sections/' + id + '/reset-code')
                .then(response => {
                    this.getAccessCode(id);
                })
                .catch(error => {
                    this.accessCodeLoaded = true;
                    this.error = error.response.data.message || error.message;
                })
            },
            tableLoad()
            {
                $(document).ready(
                    function() {
                        $('#section_table').DataTable({
                         dom: 'lBfrtip',
                        "responsive": true,
                        "autoWidth": false,
                        "pageLength": 10,
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
                                title: 'ClassesExport',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5]
                                },

                            },
                            {
                                extend: 'excelHtml5',
                                text: '<i class="far fa-file-excel mr-2"></i> Excel',
                                title: 'ClassesExport',
                                exportOptions: {
                                    columns: [ 0, 1, 2, 3, 4, 5]
                                },

                            },
                            {
                                extend: 'print',
                                text: '<i class="fas fa-print mr-2"></i> Print',
                                title: 'Class Lists',
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
