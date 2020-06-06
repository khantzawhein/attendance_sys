import Home from './views/Home.vue'
import Teachers from './views/Teachers/Teachers.vue'
import TeachersCreate from './views/Teachers/TeachersCreate.vue'
import Students from './views/Students/Students.vue'
import Courses from "./views/Courses/Courses.vue";

const Routes = [
        {
           path: '/app',
           name: 'home',
           component: Home,
        },
       {
           path: '/app/teachers',
           name: 'teachers',
           component: Teachers,
        },
       {
           path: '/app/teachers/create',
           name: 'teacher.create',
           component: TeachersCreate,
       },
       {
           path: '/app/students',
           name: 'students',
           component: Students,
       },
       {
           path: '/app/courses',
           name: 'courses',
           component: Courses,
       },
       {
           path: '/app/courses/create',
           name: 'courses.create',
           component: require('./views/Courses/CoursesCreate.vue').default,
       },
    ];
export default Routes;
