<template>
    <div>
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
        <div class="row">
            <div class="col-12">
                <form action="#" @submit.prevent="handleSubmit">
                    <div class="form-group">
                        <label for="name">Term Name</label>
                        <input type="text" id="name" class="form-control" v-model="formData.name" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="start_date">Start Date</label>
                        <input type="text" id="start_date" class="form-control" data-language='en' data-date-format="yyyy/mm/dd" v-model="formData.start_date" required autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="end_date">End Date</label>
                        <input type="text" id="end_date" class="form-control" data-language="en" data-date-format="yyyy/mm/dd" v-model="formData.end_date" required autocomplete="off">
                    </div>
                    <button class="btn btn-primary">Create</button>
                </form>
            </div>
        </div>
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
                    Bus.$emit('flash-success', "Term create success.")
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
