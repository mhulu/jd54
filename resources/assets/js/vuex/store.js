import Vue from 'vue';
import Vuex from 'vuex';
import * as actions from './actions.js';
import * as mutations from './mutations.js';

Vue.use(Vuex);

const state = {
    loading: {
        fired: false
    },
    userInfo: {
      profile: {},
      // unreadNotifications: {},
      // notifications: {},
      rolemission: {}
    },
    weather: {}
};

const getters = {
    getUserInfo: state => state.userInfo,
    getProfile: () => state.userInfo.profile,
    // getNotification: () => state.userInfo.notifications,
    getWeather: state => state.weather,
    getPermissions: () => state.userInfo.rolemission
}

export default new Vuex.Store({
    state,
    actions,
    mutations,
    getters
});
