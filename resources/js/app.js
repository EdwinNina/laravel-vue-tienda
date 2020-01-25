require('./bootstrap');

window.Vue = require('vue');

Vue.component('category-component', require('./components/Category.vue').default);
Vue.component('product-component', require('./components/Product.vue').default);
Vue.component('client-component', require('./components/Client.vue').default);
Vue.component('provider-component', require('./components/Provider.vue').default);
Vue.component('role-component', require('./components/Role.vue').default);
Vue.component('user-component', require('./components/User.vue').default);

const app = new Vue({
    el: '#app',
    data:{
        menu:0
    },
});
