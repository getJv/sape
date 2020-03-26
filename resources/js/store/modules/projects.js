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
    fetchProjects: ({ commit }) => {
        commit("setLoadingProjects", true);
        commit("setProjects", [
            {
                data: {
                    type: "projects",
                    id: 1,
                    attributes: {
                        name: "Projeto 1",
                        description: "Este é o projeto 1",
                        workflow: [
                            {
                                data: {
                                    type: "step",
                                    id: 1,
                                    attributes: {
                                        order: 1,
                                        old_status: "Nova",
                                        new_status: 2,
                                        completed: "Em Andamento"
                                    }
                                }
                            },
                            {
                                data: {
                                    type: "step",
                                    id: 2,
                                    attributes: {
                                        order: 2,
                                        old_status: "Em Andamento",
                                        new_status: "Concluído",
                                        completed: true
                                    }
                                }
                            }
                        ]
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
    },
    fetchProject: ({ commit, getters }, projectId) => {
        commit("setLoadingProjects", true);
        //posterior axios
        commit(
            "setProject",
            getters.projects.find(item => item.data.id == projectId)
        );
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
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
