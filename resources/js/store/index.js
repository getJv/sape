import Vue from "vue";
import Vuex from "vuex";
import Projects from "./modules/projects";
import ProjectStatuses from "./modules/projectStatuses";
import ProjectWorkflows from "./modules/projectWorkflows";
import Fields from "./modules/field";
import Events from "./modules/events";
import ProjectFields from "./modules/projectFields";

Vue.use(Vuex);

export default new Vuex.Store({
    modules: {
        Projects,
        ProjectStatuses,
        ProjectWorkflows,
        Fields,
        Events,
        ProjectFields
    }
});
