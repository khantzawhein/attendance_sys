<template>
<div>
    <header-component>
        <template v-slot:title>Year</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item">Year</li>
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
                        <h3 class="card-title">Year Lists</h3>
                        <div class="card-tools">
                            <button  v-if="auth==3" class="btn btn-success" @click="$router.push({name: 'years.create'})"><i class="fas fa-plus mr-1"></i> Year</button>
                        </div>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <p v-if="years.length==0">There's nothing to show</p>
                        <table class="table table-hover table-nowrap" v-if="years.length">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Academic Year</th>
                                    <th>Year Name</th>
                                    <th v-if="auth==3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(year, index) in years">
                                    <td>{{index+1}}</td>
                                    <td>{{year.academic_year}}</td>
                                    <td>{{year.name}}</td>
                                    <td v-if="auth==3">
                                        <router-link :to="{name: 'years.manage', params:{id: year.id}}" class="btn btn-secondary">Manage</router-link>
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
        name: "Year",
        props: ['auth'],
        data() {
            return {
                years: {},
                error: null,
                loaded: false,
            }
        },
        created() {
              this.getYearData();
        },
        methods: {
            getYearData() {
                this.loaded = false;
                axios.get('/api/years')
                .then(response => {
                    this.years = response.data;
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
