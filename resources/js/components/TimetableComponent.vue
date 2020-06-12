<template>
    <div>
        <div class="card card-default">
            <div v-if="!classLoaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
            <div class="card-header">
                <div class="row">
                    <div class="col-md-12 mt-2">
                        <h3 class="card-title">Course's Classes</h3>
                    </div>
                    <div class="col-md-9 mt-3">
                        <button class="btn btn-sm bg-gradient-success" data-toggle="collapse" data-target="#timetable-input" aria-expanded="false" aria-controls="timetable-input"><i class="fas fa-plus mr-1"></i>Add class</button>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <timetable-input></timetable-input>
                    </div>
                    <div class="col-md-12">
                        <p v-if="!timetables.length">There's nothing to show</p>
                        <table class="table table-hover table-nowrap" v-if="timetables.length">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Day</th>
                                    <th>Start Time</th>
                                    <th>End Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(timetable, index) in timetables">
                                    <td>{{index+1}}</td>
                                    <td>{{timetable.day}}</td>
                                    <td>{{timetable.start_time}}</td>
                                    <td>{{timetable.end_time}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "TimetableComponent",
        data() {
            return {
                loaded: true,
                classLoaded: false,
                timetables: {}
            }
        },
        created() {
            this.getTimetableData()
        },
        mounted() {
            Bus.$on('class-input-started', () => {
                this.classLoaded = false;
            })
            Bus.$on('class-input-finished', () => {
                this.getTimetableData()
                this.classLoaded = true;
            })
        },
        methods: {
            getTimetableData() {
                this.classLoaded = false
                axios.get('/api/courses/' + this.$route.params.id + '/classes')
                .then(response => {
                    this.timetables = response.data.data;
                    this.classLoaded = true
                })
                .catch(error => {
                    toastr.error(error.message, 'Opps! Something went wrong.')
                })
            }
        }
    }
</script>

<style scoped>

</style>
