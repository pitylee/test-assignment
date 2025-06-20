/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';
import {store} from '~store';

Vue.use(VueRouter);

import routes from './routes';
import App from './components/App.vue';

Vue.component('App', App);

const router = new VueRouter({
    mode: 'history',
    routes
});

const app = new Vue({
    router,
    store,
    render: h => h(App),
}).$mount('#app');

export {
    app,
    router,
    store
};
