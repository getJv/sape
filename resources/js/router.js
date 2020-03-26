import Vue from "vue";
import VueRouter from "vue-router";
import ShowProject from "./views/ShowProject";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "home",
            component: ShowProject
        }
    ]
});
