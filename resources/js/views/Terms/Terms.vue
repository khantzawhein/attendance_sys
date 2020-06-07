<template>
    <div>
        <div class="loading text-center align-items-center justify-content-center d-flex vh-100" v-if="!loaded">
            <loader-component></loader-component>
        </div>
        <div class="error mt-4" v-if="error" >
            <div class="alert alert-danger" role="alert">
                {{error}}
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-2">
                <div>
                    <router-link :to="{name: 'terms.create'}" class="btn btn-success">Create New Term</router-link>
                </div>
                <table class="table table-striped mt-2">
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
                                <button class="btn btn-secondary">Manage</button>
                                <button @click="handleDelete(term.id)" class="btn btn-danger">Delete</button>
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
                    Bus.$emit('flash-success', "Successfully deleted")
                })
                .catch(error => {
                    this.loaded = true
                    this.error = error.response.data.message || error.message;
                })
            }
        }
    }
</script>

<style scoped>

</style>
