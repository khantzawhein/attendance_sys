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
<!--                        <div class="overlay">-->
<!--                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>-->
<!--                        </div>-->
                        <div class="inner">
                            <h3>150</h3>

                            <p>Your Courses</p>
                        </div>
                        <div class="icon">
                          <i class="ion ion-ios-bookmarks-outline"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                <!-- small box -->
                    <div class="small-box bg-success">
<!--                        <div class="overlay">-->
<!--                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>-->
<!--                        </div>-->
                        <div class="inner">
                            <h3>53<sup style="font-size: 20px">%</sup></h3>

                            <p>Overall Attendance Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                <!-- small box -->
                    <div class="small-box bg-warning">
<!--                        <div class="overlay">-->
<!--                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>-->
<!--                        </div>-->
                        <div class="inner">
                            <h3>2</h3>

                            <p>Awaiting Approval Students</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                <!-- small box -->
                    <div class="small-box bg-danger">
<!--                        <div class="overlay">-->
<!--                            <i class="fas fa-3x fa-sync-alt fa-spin"></i>-->
<!--                        </div>-->
                        <div class="inner">
                            <h3>5<sup style="font-size: 20px">%</sup></h3>

                            <p>Absent Rate</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-close-circled"></i>
                        </div>
                        <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <teacher-dash-component></teacher-dash-component>

            </div>
          </div>
        </div>
    </div>
</template>
<script>

export default {
    props: ['auth']
}
</script>
