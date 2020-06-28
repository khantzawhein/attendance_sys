require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router'
import VueProgressBar from 'vue-progressbar'
Vue.use(VueRouter)
Vue.use(VueProgressBar, {
  color: '#007bff',
  failedColor: 'red',
  thickness: '3px',

})
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */


const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const router = new VueRouter({
   mode: 'history',
   routes: require('./Routes.js').default,
   linkActiveClass: 'active'

});

let teacherRoute = ['home','teachers', 'teachers.manage',
 'students', 'students.pending', 'student.manage',
 'courses', 'courses.create', 'courses.manage',
 'semesters', 'sections', 'years'];
let studentRoute = ['home', 'teachers',
 'courses'];
window.Bus = new Vue()
const app = new Vue({
        el: '#app',
        router,
        data: {
            role: [],
            teacherRoute: teacherRoute,
            studentRoute: studentRoute
        },
        created() {
            axios.get('/api/user/role')
                .then(({data}) => {
                    this.role = data;
                })
        },
        methods: {
            logout() {
                axios.post('/logout')
                .then(response => {
                    this.$router.push('/')
                    location.reload()
                })
            }
        },
        computed: {
            auth() {
                if (this.role.includes('superadmin'))
                {
                    return 3
                }
                else if(this.role.includes('teacher'))
                {
                    return 2
                }
                else if (this.role.includes('student'))
                {
                    return 1
                }
            }
        }
    });









