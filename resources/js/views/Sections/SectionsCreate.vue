<template>
    <div>
    <header-component>
        <template v-slot:title>Create a Section</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item"><router-link :to="{name: 'sections'}">Sections</router-link></li>
            <li class="breadcrumb-item active">Create Section</li>
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
                <div class="card card-default" v-show="loaded">
                    <div class="card-header">
                        <h3 class="card-title">Create Section</h3>
                    </div>
                    <div class="card-body">
                        <form action="" @submit.prevent="handleSubmit">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Section Name</label>
                                        <input type="text" id="name" class="form-control" v-model="formData.name" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Year or Description</label>
                                        <input type="text" id="start_date" class="form-control" v-model="formData.year" required autocomplete="off" placeholder="eg. First Year">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="startTime">Class Start Time:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                </div>
                                                <input v-model="formData.start_time" type="text" class="form-control float-right" id="startTime" required>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="endTime">Class End Time:</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text"><i class="far fa-clock"></i></span>
                                                </div>
                                                <input v-model="formData.end_time" type="text" class="form-control float-right" id="endTime" required>
                                            </div>
                                        </div>
                                    </div>
                                <div class="col-md-12">
                                    <button type="submit" class="btn d-block bg-gradient-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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
        name: "SectionsCreate",
        data() {
            return {
                formData: {
                    name: "",
                    year: "",
                    start_time: "",
                    end_time: ""
                },
                error: {
                    title: null,
                    details: {}
                },
                loaded: true
            }
        },
        mounted() {
            var vm = this;
            $('#startTime').timepicker({ 'step': 15, scrollDefault: '8:45am' }).on('changeTime', function() {
                vm.formData.start_time = $("#startTime").val()
            });
            $('#endTime').timepicker({ 'step': 15, scrollDefault: '8:45am'}).on('changeTime', function() {
                vm.formData.end_time = $("#endTime").val()
            });
        },
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.post('/api/sections', this.formData)
                .then(response => {
                    this.loaded = true
                    toastr.success('Section creation successful.', 'Success')
                    this.$router.push({name: 'sections'})
                })
                .catch(error => {
                    this.loaded = true
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                })
            }
        }
    }
</script>

<style scoped>

</style>
