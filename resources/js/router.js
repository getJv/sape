import Vue from "vue";
import VueRouter from "vue-router";
import DashBoard from "./views/DashBoard";
import ShowProject from "./views/ShowProject";
import ProjectStatus from "./views/ProjectStatus";
import ProjectWorkflow from "./views/ProjectWorkflow";
import Login from "./views/Login";
import Field from "./views/Fields";
import store from "./store";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/login",
            name: "login",
            component: Login
        },
        {
            path: "/",
            name: "dashboard",
            component: DashBoard,
            beforeEnter: requireAuth
        },
        {
            path: "/show-project",
            name: "show-project",
            component: ShowProject,
            beforeEnter: requireAuth
        },
        {
            path: "/project-status",
            name: "project-status",
            component: ProjectStatus,
            beforeEnter: requireAuth
        },
        {
            path: "/project-workflow",
            name: "project-workflow",
            component: ProjectWorkflow,
            beforeEnter: requireAuth
        },
        {
            path: "/field",
            name: "fields",
            component: Field,
            beforeEnter: requireAuth
        },
        {
            path: "*",
            component: DashBoard,
            beforeEnter: requireAuth
        }
    ]
});

function requireAuth(to, from, next) {
    if (!store.getters.isAuthenticated) {
        next({
            path: "/login",
            query: { redirect: to.fullPath }
        });
    } else {
        store.dispatch("fetchProjects");
        next();
    }
}
