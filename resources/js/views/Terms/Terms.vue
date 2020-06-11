<template>
<div>
    <header-component>
        <template v-slot:title>Terms</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item">Terms</li>
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
                        <h3 class="card-title">Term Lists</h3>
                        <div class="card-tools">
                            <button class="btn btn-success" @click="$router.push({name: 'terms.create'})"><i class="fas fa-plus mr-1"></i> Terms</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <p v-if="!terms.length">There's nothing to show</p>
                        <table class="table table-hover table-nowrap" v-show="terms.length">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(term, index) in terms" :key="term.id">
                                    <td>{{index+1}}</td>
                                    <td>{{term.name}}</td>
                                    <td>{{term.start_date}}</td>
                                    <td>{{term.end_date}}</td>
                                    <td>
                                        <router-link :to="{name: 'terms.manage', params: {id : term.id}}" class="btn btn-secondary">Manage</router-link >
                                        <button @click="deleteConfirm(term.id)" class="btn btn-danger">Delete</button>
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
        name: "Terms.vue",
        data() {
            return {
                terms: {},
                error: null,
                loaded: false,
            }
        },
        created() {
              this.getTermsData();
        },
        methods: {
            getTermsData() {
                this.loaded = false;
                axios.get('/api/terms')
                .then(response => {
                    this.terms = response.data.data;
                    this.loaded = true
                })
                .catch(error => {
                    this.loaded = true
                    this.error = error.response.data.message || error.message;
                })
            },
            handleDelete(id) {
                this.loaded = false;
                axios.delete('/api/terms/' + id)
                .then(response => {
                    this.getTermsData()
                    swal("Record has been deleted.", {
                      icon: "success",
                    });
                })
                .catch(error => {
                    this.loaded = true
                    this.error = error.response.data.message || error.message;
                })
            },
            deleteConfirm(id) {
                swal({
                  title: "Are you sure?",
                  text: "Once deleted, you will not be able to recover this!",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willDelete) => {
                  if (willDelete) {
                      this.handleDelete(id)
                  }
                });
            }
        }
    }
</script>

<style scoped>

</style>
