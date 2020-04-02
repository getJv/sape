const state = {
    loadingProjectStatus: false,
    projectStatuses: null,
    selectedProject: null,
    project: null
};

const getters = {
    projectStatus: state => state.projectStatus,
    projectStatuses: state => state.projectStatuses,
    loadingProjectStatus: state => state.loadingProjectStatus
};

const actions = {
    createProjectStatus({ dispatch }, data) {
        axios
            .post("/api/project-statuses", data)
            .then(res => dispatch("fetchProjectStatuses"))
            .catch(err => console.log(err.data));
    },
    updateProjectStatus({ getters, dispatch }, data) {
        axios
            .patch("/api/project-statuses/" + data.id, {
                name: data.name,
                description: data.description,
                active: data.active,
                _method: "patch"
            })
            .then(res => dispatch("fetchProjectStatuses"))
            .catch(err => {
                console.log(err.data);
            });
    },
    fetchProjectStatuses: ({ commit }) => {
        commit("setLoadingProjectStatus", true);
        axios
            .get("/api/project-statuses")
            .then(({ data }) => {
                commit("setProjectStatuses", data);
            })
            .catch(err => console.log(err.data))
            .finally(() => commit("setLoadingProjectStatus", false));
    },
    fetchProjectStatus: ({ commit, getters }, id) => {
        commit("setLoadingProjectStatus", true);
        axios
            .get("/api/project-statuses/" + id)
            .then(res => {
                commit("setProjectStatus", res.data);
                commit("setLoadingProjectStatus", false);
            })
            .catch(err => console.log(err.data));
        commit("setLoadingProjectStatus", false);
    }
};

const mutations = {
    setProjectStatuses(state, value) {
        state.projectStatuses = value;
    },
    setProjectStatus(state, value) {
        state = value;
    },
    setLoadingProjectStatus(state, value) {
        state.loadingProjectStatus = value;
    },
    setSelectedProjectStatus(state, value) {
        state.selectedProjectStatus = value;
    },
    editProjectStatus(state, data) {
        state.projectStatus.data.attributes = data;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
