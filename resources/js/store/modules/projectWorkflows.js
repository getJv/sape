const state = {
    loadingProjectWorkflow: false,
    projectWorkflow: "",
};

const getters = {
    projectWorkflow: (state) => state.projectWorkflow,
    loadingProjectWorkflow: (state) => state.loadingProjectWorkflow,
};

const actions = {
    createProjectWorkflow({ dispatch }, data) {
        axios
            .post("/api/project-workflows", data)
            .then((res) => dispatch("fetchProjectWorkflow", data.project_id))
            .catch((err) => console.log(err.data));
    },
    removeProjectWorkflow({ dispatch }, data) {
        axios
            .delete("/api/project-workflows/" + data.project_workflow_id)
            .then((res) => dispatch("fetchProjectWorkflow", data.project_id))
            .catch((err) => console.log(err.data));
    },
    fetchProjectWorkflow: ({ commit }, projectId) => {
        commit("setLoadingProjectWorkflow", true);
        axios
            .get("/api/project-workflows/project/" + projectId)
            .then(({ data }) => {
                commit("setProjectWorkflow", data);
            })
            .catch((err) => console.log(err.data))
            .finally(() => commit("setLoadingProjectWorkflow", false));
    },
};

const mutations = {
    setProjectWorkflow(state, value) {
        state.projectWorkflow = value;
    },
    setLoadingProjectWorkflow(state, value) {
        state.loadingProjectWorkflow = value;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
