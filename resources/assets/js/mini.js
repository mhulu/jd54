require('./bootstrap')
 import store from './vuex/store.js'
Vue.component('logo', require('./components/Logo.vue'))
Vue.component('register', require('./components/Register.vue'))
Vue.component('reset', require('./components/Reset.vue'))
// const app = new Vue({
//     el: '#app'
// })
new Vue(Vue.util.extend({store})).$mount('#app')
