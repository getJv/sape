const state = {
    attachments: null,
    loadingFiles: false
};

const getters = {
    attachments: state => state.attachments,
    loadingFiles: state => state.loadingFiles
};

const actions = {
    createAttachment({ getters, dispatch, commit }, data) {
        axios
            .post("api/attachments", data)
            .then(res => {
                dispatch("fetchProjectAttachment", getters.project.data.id);
                dispatch("fetchProjectWorkflow", getters.project.data.id);
            })
            .catch(err => console.log(err));
    },
    fetchProjectAttachment({ getters, dispatch, commit }, projectId) {
        commit("setLoadingFile", true);
        axios
            .get("api/attachments/project/" + projectId)
            .then(res => {
                commit("setAttachments", res.data);
            })
            .catch(err => console.log(err))
            .finally(commit("setLoadingFile", false));
    },
    deleteAttach({ getters, dispatch, commit }, itemId) {
        axios
            .delete("api/attachments/" + itemId)
            .then(res => {
                dispatch("fetchProjectAttachment", getters.project.data.id);
                dispatch("fetchProjectWorkflow", getters.project.data.id);
            })
            .catch(err => console.log(err));
    }
};

const mutations = {
    setAttachments(state, value) {
        state.attachments = value;
    },
    setLoadingFile(state, value) {
        state.loadingFiles = value;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
