import Vue from "vue";
import Vuex from "vuex";
import Projects from "./modules/projects";
import ProjectStatuses from "./modules/projectStatuses";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        Projects,
        ProjectStatuses
    }
});
