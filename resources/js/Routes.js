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
           name: 'teachers.create',
           component: TeachersCreate,
       },
       {
           path: '/app/teachers/:id',
           name: 'teachers.manage',
           component: require('./views/Teachers/TeacherManage.vue').default,
       },
       {
           path: '/app/students',
           name: 'students',
           component: Students,
       },
       {
           path: '/app/students/pending',
           name: 'students.pending',
           component: require('./views/Students/StudentsPending.vue').default,
       },
       {
           path: '/app/students/:id',
           name: 'students.manage',
           component: require('./views/Students/StudentsManage.vue').default,
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
       {
           path: '/app/courses/:id',
           name: 'courses.manage',
           component: require('./views/Courses/CoursesManage.vue').default,
       },
       {
           path: '/app/terms',
           name: 'terms',
           component: require('./views/Terms/Terms.vue').default,
       },
       {
           path: '/app/terms/create',
           name: 'terms.create',
           component: require('./views/Terms/TermsCreate.vue').default,
       },
       {
           path: '/app/terms/:id',
           name: 'terms.manage',
           component: require('./views/Terms/TermsManage.vue').default,
       },
       {
           path: '/app/sections',
           name: 'sections',
           component: require('./views/Sections/Sections.vue').default,
       },
       {
           path: '/app/sections/create',
           name: 'sections.create',
           component: require('./views/Sections/SectionsCreate.vue').default,
       },
       {
           path: '/app/sections/:id',
           name: 'sections.manage',
           component: require('./views/Sections/SectionsManage.vue').default,
       },
    ];
export default Routes;
