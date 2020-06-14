<template>
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          <li class="nav-item">
              <router-link :to="{name: 'home'}" class="nav-link" exact>
                    <i class="nav-icon fas fa-home"></i>
                <p>
                    Dashboard
                </p>
              </router-link>
          </li>
          <li class="nav-item has-treeview">
            <a href="#" :class="['nav-link', CurrentPathName.includes('students') ? activeClass : '' ]" >
                <i class="nav-icon fas fa-user-graduate"></i>
                <p>
                    Students
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <router-link :to="{name:'students'}" class="nav-link" exact>
                        <i class="fas fa-users nav-icon"></i>
                        <p>Student Lists</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name:'students.pending'}" class="nav-link" exact>
                        <i class="fas fa-users nav-icon"></i>
                        <p>Pending Student</p>
                    </router-link>
                </li>
            </ul>
          </li>

        <li class="nav-item has-treeview">
            <a href="#" :class="['nav-link', CurrentPathName.includes('teachers') ? activeClass : '' ]">
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                <p>
                    Teachers
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <router-link :to="{name:'teachers'}" class="nav-link" exact>
                        <i class="fas fa-users nav-icon"></i>
                        <p>Teachers Lists</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'teachers.create'}" class="nav-link"  exact>
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Create Teachers Account</p>
                    </router-link>
                </li>
            </ul>
          </li>

        <li class="nav-item has-treeview">
            <a href="#" :class="['nav-link', CurrentPathName.includes('courses') ? activeClass : '' ]">
                <i class="nav-icon fas fa-book"></i>
                <p>
                    Courses
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <router-link :to="{name:'courses'}" class="nav-link" exact>
                        <i class="fas fa-list nav-icon"></i>
                        <p>Course Lists</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'courses.create'}" class="nav-link"  exact>
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Create Course</p>
                    </router-link>
                </li>
            </ul>
          </li>
        <li class="nav-item has-treeview">
            <a href="#" :class="['nav-link', CurrentPathName.includes('terms') ? activeClass : '' ]">
                <i class="nav-icon far fa-clock"></i>
                <p>
                    Terms
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <router-link :to="{name:'terms'}" class="nav-link" exact>
                        <i class="fas fa-list nav-icon"></i>
                        <p>Term Lists</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'terms.create'}" class="nav-link"  exact>
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Create Term</p>
                    </router-link>
                </li>
            </ul>
          </li>
        <li class="nav-item has-treeview">
            <a href="#" :class="['nav-link', CurrentPathName.includes('sections') ? activeClass : '' ]">
                <i class="fas fa-chalkboard-teacher nav-icon"></i>
                <p>
                    Sections
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <router-link :to="{name:'sections'}" class="nav-link" exact>
                        <i class="fas fa-list nav-icon"></i>
                        <p>Section Lists</p>
                    </router-link>
                </li>
            </ul>
          </li>

          <li class="nav-item">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Simple Link
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
        </ul>
</template>

<script>
    export default {
        name: "SidebarComponent",
        data() {
            return {
                activeClass: "active",
            }
        },
        computed: {
            CurrentPathName() {
                return this.$route.name
            }
        },
    }
</script>

<style scoped>

</style>
