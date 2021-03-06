const state = {
    loadingProjects: false,
    projects: null,
    selectedProject: null,
    project: null
};

const getters = {
    project: state => state.project,
    projects: state => state.projects,
    loadingProjects: state => state.loadingProjects
};

const actions = {
    projectResetOrder({ dispatch }, project) {
        axios
            .patch("/api/projects/order/reset", {
                _method: "patch"
            })
            .then(res => dispatch("fetchProjects"))
            .catch(err => console.log(err.data));
    },
    projectMoveUp({ dispatch }, project) {
        axios
            .patch("/api/projects/" + project.data.id + "/up", {
                _method: "patch"
            })
            .then(res => dispatch("fetchProjects"))
            .catch(err => console.log(err.data));
    },
    projectMoveDown({ dispatch }, project) {
        axios
            .patch("/api/projects/" + project.data.id + "/down", {
                _method: "patch"
            })
            .then(res => dispatch("fetchProjects"))
            .catch(err => console.log(err.data));
    },
    createProject({ dispatch }, data) {
        axios
            .post("/api/projects", data)
            .then(res => dispatch("fetchProjects"))
            .catch(err => console.log(err.data));
    },
    updateProject({ getters, dispatch }, data) {
        axios
            .patch("/api/projects/" + getters.project.data.id, {
                name: getters.project.data.attributes.name,
                description: getters.project.data.attributes.description,
                active: getters.project.data.attributes.active,
                _method: "patch"
            })
            .then(res => dispatch("fetchProjects"))
            .catch(err => {
                console.log(err.data);
            });
    },
    fetchProjects: ({ commit, getters }) => {
        commit("setLoadingProjects", true);

        axios.defaults.headers.common[
            "Authorization"
        ] = `Bearer ${getters.user.data.attributes.access_token}`;

        axios
            .get("/api/projects")
            .then(({ data }) => {
                commit("setProjects", data);
            })
            .catch(err => console.log(err.data))
            .finally(() => commit("setLoadingProjects", false));
    },
    fetchProject: ({ commit, dispatch }, projectId) => {
        commit("setLoadingProjects", true);
        axios
            .get("/api/projects/" + projectId)
            .then(res => {
                commit("setProject", res.data);
                dispatch("fetchProjectWorkflow", projectId);
                dispatch("fetchProjectFields", projectId);
                dispatch("fetchProjectAttachment", projectId);
                dispatch("fetchProjectStatuses");
                commit("setLoadingProjects", false);
            })
            .catch(err => console.log(err.data));
        commit("setLoadingProjects", false);
    }
};

const mutations = {
    setProjects(state, value) {
        state.projects = value;
    },
    setProject(state, value) {
        state.project = value;
    },
    setLoadingProjects(state, value) {
        state.loadingProjects = value;
    },
    setSelectedProject(state, value) {
        state.selectedProject = value;
    },
    editProject(state, data) {
        state.project.data.attributes = data;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
