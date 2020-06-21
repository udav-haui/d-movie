window.Vue = require('vue');
Vue.mixin(require('./trans'));
window.Vuex = require('vuex');

Vue.component('payment-methods', require('./components/admin/config/PaymentMethods.vue').default);

// Add a request interceptor
// For Authorization api
axios.interceptors.request.use(function (config) {
    // assume your access token is stored in local storage
    // (it should really be somewhere more secure but I digress for simplicity)
    // let token = localStorage.getItem('access_token');
    let token = $('meta[name="access-token"]').attr('content')
    if (token) {
        config.headers['Authorization'] = `Bearer ${token}`
    }
    return config;
}, function (error) {
    // Do something with request error
    return Promise.reject(error);
});

const config = new Vue({
    el: '#config_content',
});
