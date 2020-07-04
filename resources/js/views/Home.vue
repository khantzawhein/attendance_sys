<template>
    <div>
        <header-component>
            <template v-slot:title>Dashboard</template>
            <template v-slot:breadcrumb>
                <li class="breadcrumb-item"><router-link :to="{name: 'home'}">Home</router-link></li>
                <li class="breadcrumb-item">Dashboard</li>
            </template>
        </header-component>
        <!-- Main content -->
        <div class="content">
          <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                <!-- small box -->
                    <div class="small-box bg-info">
                        <div v-if="!loaded" class="overlay">
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        </div>
                        <div class="inner">
                            <h3>{{dashboard.courseCount}}</h3>

                            <p>Your Courses</p>
                        </div>
                        <div class="icon">
                          <i class="ion ion-ios-bookmarks-outline"></i>
                        </div>
                        <router-link :to="{name: 'courses'}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                <!-- small box -->
                    <div class="small-box bg-success">
                        <div v-if="!loaded" class="overlay">
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        </div>
                        <div class="inner">
                            <h3>{{dashboard.presentRate}}<sup style="font-size: 20px">%</sup></h3>

                            <p>This Month's Attendance Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <router-link :to="{name: 'my_attendances'}"  class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <div class="col-lg-3 col-6">
                <!-- small box -->
                    <div class="small-box bg-success">
                        <div v-if="!loaded" class="overlay">
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        </div>
                        <div class="inner">
                            <h3>{{dashboard.lastMonthPresentRate}}<sup style="font-size: 20px">%</sup></h3>

                            <p>Last Month's Attendance Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-ios-rewind"></i>
                        </div>
                        <router-link :to="{name: 'my_attendances'}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <div v-if="auth>=2" class="col-lg-3 col-6">
                <!-- small box -->
                    <div class="small-box bg-warning">
                        <div v-if="!loaded" class="overlay">
                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>
                        </div>
                        <div class="inner">
                            <h3>{{dashboard.pendingStudentCount}}</h3>

                            <p>Awaiting Approval Students</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person"></i>
                        </div>
                        <router-link :to="{name: 'students.pending'}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></router-link>
                    </div>
                </div>
                <teacher-dash-component v-if="auth==2"></teacher-dash-component>
            </div>
              <div v-if="auth==1" class="row">
                  <div class="col-md-6">
                      <div class="card">
                          <div v-if="!loadStatus.attendance" class="overlay"><i class="fas fa-2x fa-sync-alt fa-spin"></i></div>
                          <div class="card-header border-transparent">
                            <h3 class="card-title">Latest Attendances</h3>

                            <div class="card-tools">
                              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-minus"></i>
                              </button>
                              <button type="button" class="btn btn-tool" data-card-widget="remove">
                                <i class="fas fa-times"></i>
                              </button>
                            </div>
                          </div>
                          <!-- /.card-header -->
                          <div class="card-body">
                              <my-attendance-component @loaded="loadStatus.attendance=true"></my-attendance-component>
                          </div>
                          <!-- /.card-body -->
                        </div>
                  </div>
              </div>
          </div>
        </div>
    </div>
</template>
<script>

export default {
    props: ['auth'],
    data() {
        return {
            dashboard: {
                pendingStudentCount : 0,
                presentRate: 0,
                lastMonthPresentRate: 0,
                courseCount: 0
            },
            loaded: false,
            loadStatus: {
                attendance: false
            }
        }
    },
    created() {
        this.getDashBoardData();
    },
    methods: {
        getDashBoardData() {
            this.loaded = false;
            axios.get('/api/dashboard-data')
            .then(({data}) => {
                this.dashboard = data;
                this.loaded = true;
            })
            .catch(error => {
                this.loaded = true;
                toastr.error (error, 'Error');
            })
        }
    }
}
</script>
