<template>
    <div class="col-lg-6 col-12">
        <div class="card">
            <div v-if="!loaded" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
          <div class="card-header">
            <h3 class="card-title">Your Timetable</h3>

            <div class="card-tools">
              <div class="input-group input-group-sm" style="width: 150px;">
                <input type="text" name="table_search" class="form-control float-right" placeholder="Search">

                <div class="input-group-append">
                  <button type="submit" class="btn btn-default"><i class="fas fa-search"></i></button>
                </div>
              </div>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body table-responsive p-0" style="height: 300px;">
            <table class="table table-head-fixed text-nowrap">
              <thead>
                <tr>
                    <th>#</th>
                    <th>Day</th>
                    <th>Module No.</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                    <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(my_timetable, index) in my_timetables">
                    <td>{{index+1}}</td>
                    <td>{{my_timetable.day}}</td>
                    <td>{{my_timetable.course.module_no}}</td>
                    <td>{{my_timetable.start_time | twelveHrFormat}}</td>
                    <td>{{my_timetable.end_time | twelveHrFormat}}</td>
                    <td>
                        <button @click="handleModal(my_timetable.id)" class="btn btn-sm bg-gradient-primary" data-toggle="modal" :data-target="'#code-modal'+my_timetable.id">Get Code</button>
                        <TeacherCodeComponent :id="my_timetable.id"></TeacherCodeComponent>
                    </td>
                </tr>
              </tbody>
            </table>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
    </div>
</template>

<script>
    export default {
        name: "TeacherTimetableComponent",
        data() {
            return {
                my_timetables: [],
                loaded: false,
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
            })
        },
        filters: {
            twelveHrFormat(time){
                return moment(time, 'HH:mm:ss').format("LT");
            }
        },
        methods: {
            handleModal(id)
            {
                Bus.$emit(`modal${id}open`);
            }
        }
    }
</script>

<style scoped>

</style>
