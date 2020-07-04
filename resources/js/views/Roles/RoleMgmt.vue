<template>
    <div>
    <header-component>
        <template v-slot:title>Role Management</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item">Role Management</li>
        </template>
    </header-component>
    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                    <div class="card-header">
                        <h3 class="card-title">Superadmins List</h3>
                    </div>
                    <div class="card-body table-responsive p-0">
                        <p class="m-3 text-center" v-if="superadmins.length==0">There's nothing to show</p>
                        <table v-if="superadmins.length" class="table table-bordered table-hover table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Teacher Name</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(superadmin, index) in superadmins">
                                    <td>{{index+1}}</td>
                                    <td>{{superadmin.name}}</td>
                                    <td>{{superadmin.email}}</td>
                                    <td>
                                        <button @click="confirmRemove(superadmin.id)" class="btn btn-danger">Remove as superadmin</button>
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
    import swal from 'sweetalert';
    export default {
        name: "SuperAdmins.vue",
        data() {
            return {
                superadmins: [],
                loaded: false,
            }
        },
        created() {
            this.getSuperAdminsData()
        },
        methods: {
            getSuperAdminsData() {
                axios.get('/api/roles')
                .then(({data}) => {
                    this.superadmins = data
                    this.loaded = true;
                })
                .catch(error => {
                    toastr.error(error.message, 'Error')
                })
            },
            handleRemove(id) {
                this.loaded = false;
                axios.delete('/api/roles/super-admin', {data: {user_id : id}})
                .then(response => {
                    swal("Remove successful.", {
                      icon: "success",
                    });
                    this.getSuperAdminsData()
                })
                .catch(error => {
                    toastr.error(error.message, 'Error')
                })
            },
            confirmRemove(id) {
                swal({
                  title: "Are you sure?",
                  text: "Once removed, this user will lose access to admin privilege.",
                  icon: "warning",
                  buttons: true,
                  dangerMode: true,
                })
                .then((willRemove) => {
                  if (willRemove) {
                      this.handleRemove(id)
                  }
                });
            }
        }
    }
</script>

<style scoped>

</style>
