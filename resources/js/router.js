import Vue from "vue";
import VueRouter from "vue-router";
import DashBoard from "./views/DashBoard";
import ShowProject from "./views/ShowProject";
import ProjectStatus from "./views/ProjectStatus";
import ProjectWorkflow from "./views/ProjectWorkflow";

Vue.use(VueRouter);

export default new VueRouter({
    mode: "history",
    routes: [
        {
            path: "/",
            name: "home",
            component: DashBoard,
        },
        {
            path: "/show-project",
            name: "show-project",
            component: ShowProject,
        },
        {
            path: "/project-status",
            name: "project-status",
            component: ProjectStatus,
        },
        {
            path: "/project-workflow",
            name: "project-workflow",
            component: ProjectWorkflow,
        },
    ],
});
