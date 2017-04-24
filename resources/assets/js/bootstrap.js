import {API_ROOT} from './config'
window._ = require('lodash');
require('../bower_components/bootstrap-sass/assets/javascripts/bootstrap.min.js');
require('./datepicker');
// require('./dropzone');
window.$ = window.jQuery = require('jquery');
window.Vue = require('vue');
window.toastr = require('toastr/build/toastr.min.js')
window.swal = require('sweetalert')
window.toastr.options = {
    positionClass: "toast-top-right"
}
window.axios = require('axios');

window.axios.interceptors.request.use(function(config){
    config.headers['X-CSRF-TOKEN'] = Wemesh.csrfToken
    return config;
})
window.axios.defaults.baseURL = API_ROOT
window.axios.defaults.params = { id: window.Wemesh.id}
window.axios.defaults.headers.post = {
	'Content-Type': 'application/x-www-form-urlencoded'
}
window.axios.interceptors.response.use(
  response => response,
  (error) => {
    if (error.response.status === 401) {
      window.location = '/login'
    }
    if (error.response.status === 404) {
    	window.location = '/'
    }
    return Promise.reject(error)
  });
Vue.prototype.$http = axios;
