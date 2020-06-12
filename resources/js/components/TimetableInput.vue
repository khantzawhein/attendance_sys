<template>
    <div class="collapse mb-2" id="timetable-input">
        <error-component :error="error"></error-component>
        <form action="#" @submit.prevent="handleSubmit">
            <div class="row justify-content-center " >
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="day">Class Name:</label>
                        <select id="day" class="form-control" v-model="formData.day">
                            <option value="" selected disabled>Select day:</option>
                            <option value="0">Monday</option>
                            <option value="1">Tuesday</option>
                            <option value="2">Wednesday</option>
                            <option value="3">Thursday</option>
                            <option value="4">Friday</option>
                            <option value="5">Saturday</option>
                            <option value="6">Sunday</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="startTime">Start Time:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                            </div>
                            <input v-model="formData.start_time" type="text" class="form-control float-right" id="startTime" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="endTime">End Time:</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="far fa-clock"></i></span>
                            </div>
                            <input v-model="formData.end_time" type="text" class="form-control float-right" id="endTime" required>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="row">
                        <div class="ml-auto">
                            <button class="btn btn-sm bg-gradient-primary" >Save</button>
                            <button type="button" class="btn btn-sm bg-gradient-gray" @click='collapse'>Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
    export default {
        name: "TimetableInput",
        data() {
            return {
                formData: {
                    day: "",
                    start_time: "",
                    end_time: ""
                },
                error: {
                    title: null,
                    details: {}
                },
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
            collapse() {
                $("#timetable-input").collapse('hide');
            },
            handleSubmit() {
                Bus.$emit('class-input-started');
                axios.post('/api/courses/' + this.$route.params.id + '/classes', this.formData)
                .then(response => {
                    Bus.$emit('class-input-finished');
                    toastr.success('Timetable added successfully', 'Success')
                    this.formData = {}
                    this.error.title = this.error.details = null
                })
                .catch(error => {
                    Bus.$emit('class-load-finished');
                    this.error.title = error.response.data.message || error.message;
                    this.error.details = error.response.data.errors;
                })
            }
        }
    }
</script>

<style scoped>

</style>
