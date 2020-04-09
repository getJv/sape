const state = {
    loadingField: false,
    fields: null,
    selectedField: null,
    field: null
};

const getters = {
    field: state => state.field,
    fields: state => state.fields,
    loadingField: state => state.loadingField
};

const actions = {
    createField({ dispatch }, data) {
        axios
            .post("/api/fields", data)
            .then(res => dispatch("fetchFields"))
            .catch(err => console.log(err.data));
    },
    updateField({ getters, dispatch }, data) {
        axios
            .patch("/api/fields/" + data.id, {
                name: data.name,
                description: data.description,
                max: data.max,
                min: data.min,
                mask: data.mask,
                type: data.type,
                active: data.active,
                _method: "patch"
            })
            .then(res => dispatch("fetchFields"))
            .catch(err => {
                console.log(err.data);
            });
    },
    fetchFields: ({ commit }) => {
        commit("setLoadingField", true);
        axios
            .get("/api/fields")
            .then(({ data }) => {
                commit("setFields", data);
            })
            .catch(err => console.log(err.data))
            .finally(() => commit("setLoadingField", false));
    },
    fetchField: ({ commit, getters }, id) => {
        commit("setLoadingField", true);
        axios
            .get("/api/field/" + id)
            .then(res => {
                commit("setField", res.data);
                commit("setLoadingField", false);
            })
            .catch(err => console.log(err.data));
        commit("setLoadingField", false);
    }
};

const mutations = {
    setFields(state, value) {
        state.fields = value;
    },
    setField(state, value) {
        state = value;
    },
    setLoadingField(state, value) {
        state.loadingProjectStatus = value;
    },
    setSelectedField(state, value) {
        state.selectedField = value;
    },
    editField(state, data) {
        state.field.data.attributes = data;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
