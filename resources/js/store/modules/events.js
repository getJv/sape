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
    updateEvent({ getters, dispatch }, data) {
        axios
            .patch("/api/project-events/" + getters.event.data.id, {
                name: getters.event.data.attributes.name,
                description: getters.event.data.attributes.description,
                owner_id: getters.event.data.attributes.owner_id,
                active: getters.event.data.attributes.active,
                _method: "patch"
            })
            .then(res => dispatch("fetchProjectWorkflow"))
            .catch(err => {
                console.log(err.data);
            });
    },
    fetchProject: ({ commit, dispatch }, eventId) => {
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
