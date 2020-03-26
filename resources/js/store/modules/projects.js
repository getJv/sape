const state = {
    loadingProjects: false,
    projects: null
};

const getters = {
    projects: state => state.projects,
    loadingProjects: state => state.loadingProjects
};

const actions = {
    fetchProjects: ({ commit }) => {
        commit("setLoadingProjects", true);
        commit("setProjects", [
            {
                data: {
                    type: "projects",
                    id: 1,
                    attributes: {
                        name: "Projeto 1",
                        description: "Este é o projeto 1"
                    }
                }
            },
            {
                data: {
                    type: "projects",
                    id: 2,
                    attributes: {
                        name: "Projeto 2",
                        description: "Este é o projeto 2"
                    }
                }
            }
        ]);
        commit("setLoadingProjects", false);
    }
};

const mutations = {
    setProjects(state, value) {
        state.projects = value;
    },
    setLoadingProjects(state, value) {
        state.loadingProjects = value;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
