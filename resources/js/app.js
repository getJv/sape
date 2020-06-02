import Vue from "vue";
import router from "./router";
import App from "./components/App";
import store from "./store";
import Vuetify from "vuetify";
import VueCurrencyFilter from "vue-currency-filter";
import "@mdi/font/css/materialdesignicons.css";
import Vuelidate from "vuelidate";
/* @ts-ignore */
Vue.use(Vuetify, Vuelidate);

require("./bootstrap");

Vue.use(VueCurrencyFilter, {
    symbol: "",
    thousandsSeparator: ".",
    fractionCount: 2,
    fractionSeparator: ",",
    symbolPosition: "front",
    symbolSpacing: true
});

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
