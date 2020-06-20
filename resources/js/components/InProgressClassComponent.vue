<template>
    <div class="col-md-6">
        <div class="card card-success">
            <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
          <div class="card-header">
            <h3 class="card-title">In Progress Class</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
              </button>
            </div>
            <!-- /.card-tools -->
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0">
              <div v-if="!timetables.length">
                  <p class="text-center p-2">There is no course in progress.</p>
              </div>
              <div v-if="timetables.length">
                  <p class="text-center p-2">There are {{timetables.length}} course(s) in progress</p>
                  <table class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
                    <th>Module No</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Action</th>
                  </tr>
                  </thead>
                  <tbody>
                    <tr v-for="timetable in timetables">
                        <td>{{timetable.course.module_no}}</td>
                        <td>{{timetable.start_time | twelveHrFormat}}</td>
                        <td>{{timetable.end_time | twelveHrFormat}}</td>
                        <td>
                            <button class="btn btn-sm bg-gradient-primary">Get Code</button>
                        </td>
                    </tr>
                  </tbody>
                </table>
              </div>

          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        name: "InProgressClassComponent",
        data() {
            return {
                my_timetables: [],
                timetables: [],
                loaded: false,
            }
        },
        filters: {
            twelveHrFormat(time){
                return moment(time, 'HH:mm:ss').format("LT");
            }
        },

        created() {
            moment.updateLocale("en", { week: {
              dow: 1, // First day of week is Monday
              doy: 4  // First week of year must contain 4 January (7 + 1 - 4)
            }});
            Bus.$on('my_timetable_loaded', (data) => {
                this.my_timetables = data
                this.loaded = true

                this.my_timetables.forEach((timetable) => {
                    let start_time = moment(`${timetable.day} ${timetable.start_time}`, 'dddd HH:mm:ss')
                    let end_time = moment(`${timetable.day} ${timetable.end_time}`, 'dddd HH:mm:ss')
                    if (moment().isBetween(start_time, end_time)) {
                        this.timetables.push(timetable)
                    }
                })
            })

        },
    }
</script>

<style scoped>

</style>
