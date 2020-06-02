const state = {
    selectedEvent: null,
    event: null,
    loadingEvent: false
};

const getters = {
    event: state => state.event,
    loadingEvent: state => state.loadingEvent
};

const actions = {
    createEvent({ getters, dispatch }, data) {
        axios
            .post("/api/project-events", data)
            .then(res =>
                dispatch("fetchProjectWorkflow", getters.project.data.id)
            )
            .catch(err => console.log(err.data));
    },
    deleteEvent({ getters, dispatch }, eventId) {
        axios
            .delete("/api/project-events/" + eventId)
            .then(res =>
                dispatch("fetchProjectWorkflow", getters.project.data.id)
            )
            .catch(err => console.log(err.data));
    },
    updateEvent({ getters, dispatch }, data) {
        axios
            .patch("/api/project-events/" + data.id, {
                name: data.name,
                description: data.description,
                project_workflow_id: data.project_workflow_id,
                owner_id: data.owner_id,
                active: data.active,
                _method: "patch"
            })
            .then(res => dispatch("fetchProjectWorkflow", project_workflow_id))
            .catch(err => {
                console.log(err.data);
            });
    },
    fetchEvent: ({ commit, dispatch }, eventId) => {
        commit("setLoadingEvent", true);
        axios
            .get("/api/project-events/" + eventId)
            .then(res => {
                commit("setEvent", res.data);
                commit("setLoadingEvent", false);
            })
            .catch(err => console.log(err.data));
        commit("setLoadingEvent", false);
    }
};

const mutations = {
    setEvent(state, value) {
        state.Event = value;
    },
    setLoadingEvent(state, value) {
        state.loadingEvent = value;
    },
    setSelectedEvent(state, value) {
        state.selectedEvent = value;
    },
    editEvent(state, data) {
        state.project.data.attributes = data;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
