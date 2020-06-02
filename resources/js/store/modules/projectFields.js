const state = {
    loadingProjectField: false,
    projectFields: null,
    allProjectFields: null
};

const getters = {
    projectFields: state => state.projectFields,
    allProjectFields: state => state.allProjectFields,
    loadingProjectField: state => state.loadingProjectField
};

const actions = {
    projectFieldResetOrder({ dispatch }, projectId) {
        axios
            .patch("/api/project-fields/project/" + projectId + "/reset", {
                _method: "patch"
            })
            .then(res => dispatch("fetchProjectFields", projectId))
            .catch(err => console.log(err.data));
    },
    projectFieldMoveUp({ dispatch }, item) {
        axios
            .patch("/api/project-fields/" + item.data.id + "/up", {
                _method: "patch"
            })
            .then(res =>
                dispatch("fetchProjectFields", item.data.attributes.project_id)
            )
            .catch(err => console.log(err.data));
    },
    projectFieldMoveDown({ dispatch }, item) {
        axios
            .patch("/api/project-fields/" + item.data.id + "/down", {
                _method: "patch"
            })
            .then(res =>
                dispatch("fetchProjectFields", item.data.attributes.project_id)
            )
            .catch(err => console.log(err.data));
    },

    createProjectField({ dispatch }, data) {
        axios
            .post("/api/project-fields", data)
            .then(res => dispatch("fetchProjectFields", data.project_id))
            .catch(err => console.log(err.data));
    },
    updateProjectField({ dispatch }, data) {
        data._method = "patch";
        axios
            .patch("/api/project-fields/" + data.project_field_id, data)
            .then(res => dispatch("fetchProjectFields", data.project_id))
            .catch(err => console.log(err.data))
            .finally(() => dispatch("fetchProjectFields", data.project_id));
    },
    fetchProjectFields: ({ commit }, projectId) => {
        commit("setLoadingProjectField", true);
        axios
            .get("/api/project-fields/project/" + projectId)
            .then(({ data }) => {
                commit("setProjectFields", data);
            })
            .catch(err => console.log(err.data))
            .finally(() => commit("setLoadingProjectField", false));
    },
    fetchAllProjectFields: ({ commit }) => {
        commit("setLoadingProjectField", true);
        axios
            .get("/api/project-fields/")
            .then(({ data }) => {
                commit("setAllProjectFields", data);
            })
            .catch(err => console.log(err.data))
            .finally(() => commit("setLoadingProjectField", false));
    }
};

const mutations = {
    setProjectFields(state, value) {
        state.projectFields = value;
    },
    setAllProjectFields(state, value) {
        state.allProjectFields = value;
    },
    setLoadingProjectField(state, value) {
        state.loadingProjectField = value;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
