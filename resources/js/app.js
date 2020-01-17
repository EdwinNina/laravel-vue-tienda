require('./bootstrap');

window.Vue = require('vue');

Vue.component('category-component', require('./components/Category.vue').default);
Vue.component('product-component', require('./components/Product.vue').default);

const app = new Vue({
    el: '#app',
    data:{
        menu:0
    },
});
