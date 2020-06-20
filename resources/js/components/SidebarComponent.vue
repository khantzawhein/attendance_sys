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
        <li v-if="auth==1" class="nav-item">
            <router-link :to="{name: 'my_classes'}" :class="['nav-link', CurrentPathName.includes('my_classes') ? activeClass : '' ]" >
                <i class="nav-icon fas fa-chalkboard-teacher"></i>
                <p>
                    My Classes
                </p>
            </router-link>
          <li v-if="auth>=2" class="nav-item has-treeview">
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

        <li v-if="auth==3" class="nav-item has-treeview">
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
        <li v-if="auth==3" class="nav-item has-treeview">
            <a href="#" :class="['nav-link', CurrentPathName.includes('years') ? activeClass : '' ]">
                <i class="nav-icon far fa-calendar"></i>
                <p>
                    Years
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <router-link :to="{name:'years'}" class="nav-link" exact>
                        <i class="fas fa-users nav-icon"></i>
                        <p>Year Lists</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'years.create'}" class="nav-link"  exact>
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Create Year</p>
                    </router-link>
                </li>
            </ul>
          </li>
        <li v-if="auth==3" class="nav-item has-treeview">
            <a href="#" :class="['nav-link', CurrentPathName.includes('terms') ? activeClass : '' ]">
                <i class="nav-icon far fa-clock"></i>
                <p>
                    Semesters
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <router-link :to="{name:'semesters'}" class="nav-link" exact>
                        <i class="fas fa-list nav-icon"></i>
                        <p>Semester Lists</p>
                    </router-link>
                </li>
                <li class="nav-item">
                    <router-link :to="{name: 'semesters.create'}" class="nav-link"  exact>
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Create Semester</p>
                    </router-link>
                </li>
            </ul>
          </li>
        <li v-if="auth>=2" class="nav-item has-treeview">
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
                <li v-if="auth>=2" class="nav-item">
                    <router-link :to="{name: 'courses.create'}" class="nav-link"  exact>
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Create Course</p>
                    </router-link>
                </li>
            </ul>
          </li>

        <li v-if="auth>=1" class="nav-item has-treeview">
            <router-link :to="{name: 'courses'}" :class="['nav-link', CurrentPathName.includes('courses') ? activeClass : '' ]">
                <i class="nav-icon fas fa-book"></i>
                <p>
                    My Courses
                </p>
            </router-link>
        </li>

        <li v-if="auth>=2" class="nav-item has-treeview">
            <a href="#" :class="['nav-link', CurrentPathName.includes('sections') ? activeClass : '' ]">
                <i class="fas fa-chalkboard-teacher nav-icon"></i>
                <p>
                    Classes
                    <i class="right fas fa-angle-left"></i>
                </p>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                    <router-link :to="{name:'sections'}" class="nav-link" exact>
                        <i class="fas fa-list nav-icon"></i>
                        <p>Class Lists</p>
                    </router-link>
                </li>
                <li class="nav-item" v-if="auth==3">
                    <router-link :to="{name: 'sections.create'}" class="nav-link"  exact>
                        <i class="fas fa-plus nav-icon"></i>
                        <p>Create Class</p>
                    </router-link>
                </li>
            </ul>
          </li>

    </ul>

</template>

<script>
    export default {
        name: "SidebarComponent",
        props: ['auth'],
        data() {
            return {
                activeClass: "active",
            }
        },
        computed: {
            CurrentPathName() {
                if(this.$route.name === null)
                {
                    return []
                }
                return this.$route.name
            },
        },
    }
</script>

<style scoped>

</style>
