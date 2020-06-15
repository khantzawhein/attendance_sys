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
                            <button class="btn btn-success" @click="$router.push({name: 'sections.create'})"><i class="fas fa-plus mr-1"></i> Class</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p v-if="!sections.length">There's nothing to show</p>
                        <table class="table table-hover table-nowrap" v-show="sections.length">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Class Name</th>
                                    <th>Academic Year</th>
                                    <th>Year</th>
                                    <th>Semester</th>
                                    <th>Class Start At</th>
                                    <th>Class End At</th>
                                    <th>Actions</th>
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

        data() {
            return {
                sections: {},
                error: {
                    title: "",
                    details: ""
                },
                loaded: false,
            }
        },
        created() {
            this.getSectionsData();
        },
        methods: {
            getSectionsData() {
                axios.get('/api/sections')
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
            }
        }
    }
</script>

<style scoped>

</style>
