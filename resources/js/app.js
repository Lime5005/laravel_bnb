require("./bootstrap");

import moment from "moment";
import VueRouter from "vue-router";
import Vuex from 'vuex';
import Index from "./Index";
import router from "./routes";
import FatalError from "./shared/components/FatalError";
import StarRating from "./shared/components/StarRating";
import Success from "./shared/components/Success";
import ValidationErrors from "./shared/components/ValidationErrors";
import storeDefinition from "./store";

import Vue from 'vue/dist/vue'
import axios from "axios";
// window.Vue = require("vue");

Vue.use(Vuex);

window.Vue = Vue
Vue.use(VueRouter)

Vue.filter("fromNow", value => moment(value).fromNow());

Vue.component("star-rating", StarRating);
Vue.component("fatal-error", FatalError);
Vue.component("success", Success);
Vue.component("validation-errors", ValidationErrors);

const store = new Vuex.Store(storeDefinition);

// window.axios.interceptors.response.use(
//     response => {
//         return response;
//     },
//     error => {
//         if (401 === error.response.status) {
//             store.dispatch("logout");
//         }

//         return Promise.reject(error);
//     }
// );

const app = new Vue({
    el: "#app",
    router,
    store,
    components: {
        index: Index
    },
    async beforeCreate() {
        this.$store.dispatch("loadStoredState");

        await axios.get('/sanctum/csrf-cookie')
        await axios.post('login', {
            email: 'hyatt.cedrick@example.net',
            password: 'password'
        })
        await axios.get('/user')
            // this.$store.dispatch("loadUser");
    },
});