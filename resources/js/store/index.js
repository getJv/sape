import Vue from "vue";
import Vuex from "vuex";
import Projects from "./modules/projects";
import ProjectStatuses from "./modules/projectStatuses";
import ProjectWorkflows from "./modules/projectWorkflows";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        Projects,
        ProjectStatuses,
        ProjectWorkflows,
    },
});
