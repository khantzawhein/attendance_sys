<template>
    <div>
    <header-component>
        <template v-slot:title>Create a Term</template>
        <template v-slot:breadcrumb>
            <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
            <li class="breadcrumb-item"><router-link :to="{name: 'courses'}">Terms</router-link></li>
            <li class="breadcrumb-item active">Create Term</li>
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
                        <ul v-for="(error, index) in errorDetails">
                            <li>
                                <strong>{{index}}</strong>
                                <ul>
                                    <li v-for="suberror in error">
                                        {{suberror}}
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="card card-default">
                    <div class="card-header">
                        <h3 class="card-title">Create Term</h3>
                    </div>
                    <div class="card-body">
                        <form action="" @submit.prevent="handleSubmit">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name">Term Name</label>
                                        <input type="text" id="name" class="form-control" v-model="formData.name" required autocomplete="off">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="text" id="start_date" class="form-control" data-language='en' data-date-format="yyyy/mm/dd" v-model="formData.start_date" required autocomplete="off" placeholder="yyyy/mm/dd">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="text" id="end_date" class="form-control" data-language="en" data-date-format="yyyy/mm/dd" v-model="formData.end_date" required autocomplete="off" placeholder="yyyy/mm/dd">
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
        name: "TermsCreate",
        data() {
            return {
                formData: {
                    name: "",
                    start_date: "",
                    end_date: ""
                },
                error: null,
                errorDetails: null,
                loaded: false
            }
        },
        mounted() {
            this.loaded = true;
            $('#start_date').datepicker({
                onSelect: function(formattedDate, date, inst) {
                    this.formData.start_date = formattedDate
                }.bind(this)
            })
            $('#end_date').datepicker({
                onSelect: function(formattedDate, date, inst) {
                    this.formData.end_date = formattedDate
                }.bind(this)
            })
        },
        methods: {
            handleSubmit() {
                this.loaded = false
                axios.post('/api/terms', this.formData)
                .then(response => {
                    this.loaded = true
                    toastr.success('Term creation successful.', 'Success')
                    this.$router.push({name: 'terms'})
                })
                .catch(error => {
                    this.loaded = true
                    this.error = error.response.data.message || error.message;
                    this.errorDetails = error.response.data.errors;
                })
            }
        }
    }
</script>

<style scoped>

</style>
