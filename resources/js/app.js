require('./bootstrap');
window.Vue = require('vue');
import VueRouter from 'vue-router'
Vue.use(VueRouter);
/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */
import App from './views/App.vue'
import Home from './views/Home.vue'
import Teachers from './views/Teachers.vue'
import TeachersCreate from './views/TeachersCreate.vue'
import Students from './views/Students.vue'
import Courses from "./views/Courses.vue";
const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

const router = new VueRouter({
   mode: 'history',
   routes: [
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

   ]
});
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
const app = new Vue({
    el: '#app',
    components: { App },
    router,
});
