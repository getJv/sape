import Vue from "vue";
import router from "./router";
import App from "./components/App";
import store from "./store";
import Vuetify from "vuetify";
import "@mdi/font/css/materialdesignicons.css";
import Vuelidate from "vuelidate";

Vue.use(Vuetify, Vuelidate);

require("./bootstrap");

const app = new Vue({
    el: "#app",
    router,
    store,
    vuetify: new Vuetify({
        icons: {
            iconfont: "mdi" // default - only for display purposes
        }
    }),
    components: {
        App
    }
});
