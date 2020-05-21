require('./bootstrap');

window.$ = window.jQuery = require('jquery');

window.Vue = require('vue');

Vue.component('category-component', require('./components/Category.vue').default);
Vue.component('product-component', require('./components/Product.vue').default);
Vue.component('client-component', require('./components/Client.vue').default);
Vue.component('provider-component', require('./components/Provider.vue').default);
Vue.component('role-component', require('./components/Role.vue').default);
Vue.component('user-component', require('./components/User.vue').default);
Vue.component('ingreso-component', require('./components/Ingreso.vue').default);
Vue.component('venta-component', require('./components/Venta.vue').default);
Vue.component('dashboard-component', require('./components/Dashboard.vue').default);
Vue.component('consulta-ingreso-component', require('./components/ConsultaIngreso.vue').default);
Vue.component('consulta-venta-component', require('./components/ConsultaVenta.vue').default);
Vue.component('notification-component', require('./components/Notification.vue').default);

const app = new Vue({
    el: '#app',
    data:{
        menu:0,
        notifications: []
    },
    created(){
        axios.post('notification/get').then(response =>
            this.notifications = response.data
        );
        var userId = $('meta[name="userId"]').attr('content');
        Echo.private('App.User.' + userId).notification(notification => this.notifications.unshift(notification));
    }
});
