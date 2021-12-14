require('./bootstrap');


window.Vue = require('vue');

import Vuetify from '../plugin/vuetify'
import VueRouter from 'vue-router';
import {routes} from './routes';

window.moment = require('moment');
Vue.use(VueRouter);

import Swal from 'sweetalert2'
window.Swal = Swal

const toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
      toast.addEventListener('mouseenter', Swal.stopTimer)
      toast.addEventListener('mouseleave', Swal.resumeTimer)
    }
  })
  window.toast = toast

const router = new VueRouter({
    mode: 'history',
    routes: routes
});


const app = new Vue({
    vuetify: Vuetify,
    el: '#app',
    router: router

});
