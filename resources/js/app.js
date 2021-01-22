/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

//require('./bootstrap');

window.Vue = require('vue').default;

/**
 * Bootstrap Vue
 */
require('./bootstrap');

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue';
import 'bootstrap-vue/dist/bootstrap-vue.css';

Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

/**
 * Axios
 */
import axios from 'axios';
import VueAxios from 'vue-axios';

Vue.use(VueAxios, axios);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('invitation-list', require('./components/InvitationList').default);
Vue.component('delete-account-form', require('./components/DeleteAccountForm').default);
Vue.component('receipt-list', require('./components/ReceiptList').default);
Vue.component('notification-list', require('./components/NotificationList').default);
Vue.component('notification-icon', require('./components/NotificationIcon').default);
Vue.component('loading-button', require('./components/LoadingButton').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data() {
        return {
            newNotification: null,
            visibleNotificationSidebar: false,
        }
    },
    methods: {
        notificationSidebarShown() {
            this.newNotification = false;
            this.visibleNotificationSidebar = true;
        },
        notificationSidebarHidden() {
            this.visibleNotificationSidebar = false;
        }
    }
});
