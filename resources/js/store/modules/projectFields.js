const state = {
    loadingProjectField: false,
    projectFields: "",
};

const getters = {
    projectFields: (state) => state.projectFields,
    loadingProjectField: (state) => state.loadingProjectField,
};

const actions = {
    createProjectField({ dispatch }, data) {
        axios
            .post("/api/project-fields", data)
            .then((res) => dispatch("fetchProjectFields", data.project_id))
            .catch((err) => console.log(err.data));
    },
    updateProjectField({ dispatch }, data) {
        data._method = "patch";
        axios
            .patch("/api/project-fields/" + data.project_field_id, data)
            .then((res) => dispatch("fetchProjectFields", data.project_id))
            .catch((err) => console.log(err.data))
            .finally(() => dispatch("fetchProjectFields", data.project_id));
    },
    fetchProjectFields: ({ commit }, projectId) => {
        commit("setLoadingProjectField", true);
        axios
            .get("/api/project-fields/project/" + projectId)
            .then(({ data }) => {
                commit("setProjectFields", data);
            })
            .catch((err) => console.log(err.data))
            .finally(() => commit("setLoadingProjectField", false));
    },
};

const mutations = {
    setProjectFields(state, value) {
        state.projectFields = value;
    },
    setLoadingProjectField(state, value) {
        state.loadingProjectField = value;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
};
