const state = {
    loadingDashboards: false,
    dashboards: null
};

const getters = {
    dashboards: state => state.dashboards,
    loadingDashboards: state => state.loadingDashboards
};

const actions = {
    deleteDashboard({ getters, dispatch, commit }, itemId) {
        axios
            .delete("api/dashboards/" + itemId)
            .then(res => {
                dispatch("fetchDashboards");
            })
            .catch(err => console.log(err));
    },

    createDashboard({ dispatch }, data) {
        axios
            .post("/api/dashboards", data)
            .then(res => dispatch("fetchDashboards"))
            .catch(err => console.log(err.data));
    },
    fetchDashboards: ({ commit, dispatch }, projectId) => {
        commit("setLoadingDashboards", true);
        axios
            .get("/api/dashboards/")
            .then(res => {
                commit("setDashboards", res.data);
            })
            .catch(err => console.log(err.data))
            .finally(() => {
                commit("setLoadingDashboards", false);
            });
    }
};

const mutations = {
    setDashboards(state, value) {
        state.dashboards = value;
    },
    setLoadingDashboards(state, value) {
        state.loadingDashboards = value;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
