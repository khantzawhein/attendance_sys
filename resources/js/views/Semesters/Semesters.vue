<template>
<div>
    <header-component>
        <template v-slot:title>Semesters</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item">Semesters</li>
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
                <div class="error mt-4" v-if="error" >
                    <div class="alert alert-danger" role="alert">
                        <strong>{{error}}</strong>
                    </div>
                </div>
                <div class="card" v-show="loaded&&!error">
                    <div class="card-header">
                        <h3 class="card-title">Semester Lists</h3>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="$router.push({name: 'semesters.create'})"><i class="fas fa-plus mr-1"></i> Semesters</button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <p v-if="semesters.length==0">There's nothing to show</p>
                        <div class="accordion" id="semester-accordion">
                            <div class="card" v-for="(data, index) in semesters">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" :data-target="`#sem${index}`" aria-expanded="true" :aria-controls="`sem${index}`">
                                        {{index}}
                                        </button>
                                    </h2>
                                </div>
                                <div :id="`sem${index}`" class="collapse show" aria-labelledby="headingOne" data-parent="#semester-accordion">
                                    <div class="card-body">
                                        <table class="table table-hover table-nowrap">
                                            <thead>
                                                <tr>
                                                    <th>ID</th>
                                                    <th>Academic Year</th>
                                                    <th>Year</th>
                                                    <th>Semester Name</th>

                                                    <th>Start Date</th>
                                                    <th>End Date</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr v-for="(semester, index) in data" :key="semester.id">
                                                    <td>{{index+1}}</td>
                                                    <td>{{semester.academic_year}}</td>
                                                    <td>{{semester.year}}</td>
                                                    <td>{{semester.semester_name}}</td>
                                                    <td>{{semester.start_date}}</td>
                                                    <td>{{semester.end_date}}</td>
                                                    <td>
                                                        <router-link :to="{name: 'semesters.manage', params: {id : semester.id}}" class="btn btn-secondary">Manage</router-link >
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
    <!-- /.content -->
  </div>
</template>

<script>
    export default {
        name: "Semesters.vue",
        data() {
            return {
                semesters: {},
                originalData: {},
                error: null,
                loaded: false,
            }
        },
        created() {
              this.getSemestersData();
        },
        methods: {
            getSemestersData() {
                this.loaded = false;
                axios.get('/api/semesters')
                .then(response => {
                    this.originalData = response.data.data;
                    this.semesters = _.groupBy(this.originalData, 'academic_year')
                    this.loaded = true
                })
                .catch(error => {
                    this.loaded = true
                    this.error = error.response.data.message || error.message;
                })
            },
        }
    }
</script>

<style scoped>

</style>
