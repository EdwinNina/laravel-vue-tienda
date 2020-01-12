require('./bootstrap');

window.Vue = require('vue');

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('category-component', require('./components/Category.vue').default);

const app = new Vue({
    el: '#app',
    data:{
        menu:0
    }
});
