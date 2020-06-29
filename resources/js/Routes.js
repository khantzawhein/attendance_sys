import Home from './views/Home.vue'
import Teachers from './views/Teachers/Teachers.vue'
import TeachersCreate from './views/Teachers/TeachersCreate.vue'
import Students from './views/Students/Students.vue'
import Courses from "./views/Courses/Courses.vue";

let teacherRoute = ['home','teachers', 'teachers.manage',
 'students', 'students.pending', 'students.manage',
 'courses', 'courses.create', 'courses.manage', 'courses.attendances',
 'semesters', 'sections', 'sections.manage', 'years', 'change_password',
 'edit_teacher_info'];
let studentRoute = ['home' ,'my_classes', 'courses', 'attendance', 'sections.timetable','my_attendances',
    'change_password', ];
let role = null;
async function guard(to, from, next)
{
    if(!role) {
        const {data} = await axios.get('/api/user/role')
        role = data;
    }
    if (role.includes('superadmin')) {
        next();
    }
    else if(role.includes('teacher'))
    {
        if(teacherRoute.includes(to.name)) {
            next();
        }
        else {
            next('/app');
        }
    }
    else if(role.includes('student'))
    {
        if(studentRoute.includes(to.name)) {
            next()
        }
        else {
            next('/app');
        }
    }
    else {
        next('/app')
    }

}
const Routes = [
        {
           path: '/app',
           name: 'home',
           component: Home,
           beforeEnter: guard,
        },
       {
           path: '/app/teachers',
           name: 'teachers',
           component: Teachers,
           beforeEnter: guard,
        },
       {
           path: '/app/teachers/create',
           name: 'teachers.create',
           component: TeachersCreate,
           beforeEnter: guard,
       },
       {
           path: '/app/teachers/:id',
           name: 'teachers.manage',
           component: require('./views/Teachers/TeacherManage.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/students',
           name: 'students',
           component: Students,
           beforeEnter: guard,
       },
       {
           path: '/app/students/pending',
           name: 'students.pending',
           component: require('./views/Students/StudentsPending.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/students/:id',
           name: 'students.manage',
           component: require('./views/Students/StudentsManage.vue').default,
           beforeEnter: guard,
       },

       {
           path: '/app/courses',
           name: 'courses',
           component: Courses,
           beforeEnter: guard,
       },
       {
           path: '/app/courses/create',
           name: 'courses.create',
           component: require('./views/Courses/CoursesCreate.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/courses/:id',
           name: 'courses.manage',
           component: require('./views/Courses/CoursesManage.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/courses/:id/attendances',
           name: 'courses.attendances',
           component: require('./views/Courses/CourseAttendances.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/semesters',
           name: 'semesters',
           component: require('./views/Semesters/Semesters.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/semesters/create',
           name: 'semesters.create',
           component: require('./views/Semesters/SemestersCreate.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/semesters/:id',
           name: 'semesters.manage',
           component: require('./views/Semesters/SemestersManage.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/sections',
           name: 'sections',
           component: require('./views/Sections/Sections.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/sections/create',
           name: 'sections.create',
           component: require('./views/Sections/SectionsCreate.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/sections/:id',
           name: 'sections.manage',
           component: require('./views/Sections/SectionsManage.vue').default,
           beforeEnter: guard,
       },
        {
           path: '/app/sections/:id/timetable',
           name: 'sections.timetable',
           component: require('./views/Sections/SectionsTimetable').default,
           beforeEnter: guard,
       },
        {
           path: '/app/years',
           name: 'years',
           component: require('./views/Years/Years.vue').default,
            beforeEnter: guard,
       },
       {
           path: '/app/years/create',
           name: 'years.create',
           component: require('./views/Years/YearsCreate.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/years/:id',
           name: 'years.manage',
           component: require('./views/Years/YearsManage.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/my_classes',
           name: 'my_classes',
           component: require('./views/MyClasses/MyClasses.vue').default,
           beforeEnter: guard,
       },
        {
           path: '/app/attendance',
           name: 'attendance',
           component: require('./views/Attendance/Attendance.vue').default,
           beforeEnter: guard,
       },
        {
           path: '/app/my_attendances',
           name: 'my_attendances',
           component: require('./views/MyAttendances/MyAttendances.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/change_password',
           name: 'change_password',
           component: require('./views/MyAccount/ChangePassword.vue').default,
           beforeEnter: guard,
       },
       {
           path: '/app/edit_teacher',
           name: 'edit_teacher_info',
           component: require('./views/MyAccount/EditTeacherInfo.vue').default,
           beforeEnter: guard,
       },


    ];
export default Routes;
