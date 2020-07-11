<template>
    <div>
        <header-component>
            <template v-slot:title>My Classes</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item">My Classes</li>
            </template>
        </header-component>
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <error-component :error="error"></error-component>
                    <div class="card card-default">
                        <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                        <div class="card-header">
                            <h3 class="card-title">My Classes</h3>
                            <div class="card-tools">
                                <button  v-if="auth==1" class="btn btn-sm bg-gradient-success" data-toggle="collapse" data-target="#collapse_component" aria-expanded="false" aria-controls="collapse_component"><i class="fas fa-plus mr-1"></i>Add class</button>
                            </div>
                        </div>
                        <collapse-component @collapse_submitted="handleSubmit">
                            <template v-slot:body>
                                <div class="col-md-12 mt-2">
                                    <form action="#">
                                        <div class="row justify-content-center">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label for="access_code">Class Enrollment Code:</label>
                                                    <input v-model="formData.access_code" type="text" class="form-control" id="access_code">
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </template>
                        </collapse-component>
                        <div class="card-body">
                            <p v-if="my_classes.length==0">There's nothing to show</p>
                                <div class="accordion" id="days_slide" v-for="(data, index) in my_classes">
                                <div class="card">
                                    <div class="card-header" id="headingOne">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link btn-block text-left collapsed" type="button" data-toggle="collapse" :data-target="`#course${data[0].course_id}`" aria-expanded="true" :aria-controls="`#course${data[0].course_id}`">
                                                {{index}}
                                            </button>
                                        </h2>
                                    </div>

                                    <div :id="`course${data[0].course_id}`" class="collapse" aria-labelledby="headingOne" data-parent="#days_slide">
                                        <div class="card-body table-responsive p-0">
                                            <table class="table table-hover table-nowrap" >
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Section Name</th>
                                                        <th>Academic Year</th>
                                                        <th>Semester</th>
                                                        <th>Teacher Name</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(my_classes, index) in data">
                                                        <td>{{index+1}}</td>
                                                        <td>{{my_classes.section_name}}</td>
                                                        <td>{{my_classes.academic_year}}</td>
                                                        <td>{{my_classes.year}}</td>
                                                        <td>{{my_classes.semester_name}}</td>
                                                        <td>
                                                            <router-link :to="{name: 'sections.timetable', params: {id: my_classes.id}}" class="btn bg-gradient-primary">Timetable</router-link>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
        name: "MyClasses",
        props: ['auth'],
        data() {
            return {
                formData: {
                    access_code: ""
                },
                loaded: false,
                my_classes: {},
                error: {
                    title: null,
                    details: {}
                },
            }
        },
        created() {
            this.getMyCourseData()
        },
        methods: {
            getMyCourseData() {
                 axios.get('/api/my_classes')
                    .then(({data}) => {
                        this.my_classes = _.groupBy(data.data, 'year')
                        this.loaded = true
                    })
                    .catch(error => {
                        this.loaded = true
                        toastr.error(error.message, 'Error')
                    })
            },
            handleSubmit() {
                this.loaded = false;
                axios.post('/api/my_classes', this.formData)
                .then(() => {
                    this.getMyCourseData()
                    this.formData.access_code = ""
                    toastr.success('Class has been binded to your account.', 'Success')
                })
                .catch(() => {
                    toastr.error('Incorrect access code', 'Sorry');
                    this.loaded = true;
                })
            }
        }
    }
</script>

<style scoped>

</style>
