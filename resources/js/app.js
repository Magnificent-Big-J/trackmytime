
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');
import swal from 'sweetalert2'
window.swal =swal
const toast= swal.mixin({
    toast:true,
    position:'top-end',
    showConfirmButton:false,
    timer:3000
})

window.toast =toast
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('example-component', require('./components/ExampleComponent.vue'));
Vue.component('app-projects', require('./components/Projects.vue'));
Vue.component('app-breakdown', require('./components/BreakDown'));
Vue.component('app-dashboard', require('./components/Dashboard'));

const app = new Vue({
    el: '#app'
});
