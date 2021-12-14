require('./bootstrap');


window.Vue = require('vue');

import Vuetify from '../plugin/vuetify'
import VueRouter from 'vue-router';
import {routes} from './routes';

window.moment = require('moment');



Vue.use(VueRouter);


const router = new VueRouter({
    mode: 'history',
    routes: routes
});


const app = new Vue({
    vuetify: Vuetify,
    el: '#app',
    router: router

});
