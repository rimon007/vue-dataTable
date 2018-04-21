
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('v-datatable', require('./components/VDatatable.vue'));
Vue.component('v-table', require('./components/VTable.vue'));
Vue.component('pagination', require('./components/Pagination.vue'));

const app = new Vue({
    el: '#app',
    methods: {
    	handleSearchByOwn() {
    		console.log('This is own search handler: ', event.target.value)
    	},
    	handleActionByOwn(data) {
    		console.log('This is own search handler: ', data)
    	}
    }
});
