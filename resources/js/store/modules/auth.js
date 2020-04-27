const state = {
    user: null,
    loading: false,
    sessionKey: "LoggedUser"
};

const getters = {
    user: state => state.user,
    loadingLogin: state => state.loadingLogin,
    isAuthenticated: state => {
        var result = state.user || !!sessionStorage.getItem(state.sessionKey);

        if (result) {
            state.user = JSON.parse(sessionStorage.getItem(state.sessionKey));
        }

        return result;
    }
};

const actions = {
    login({ getters, dispatch, commit }, data) {
        commit("setLoadingLogin", true);

        axios
            .post("/api/login", data)
            .then(res => {
                //Configuro o token no axios.
                axios.defaults.headers.common[
                    "Authorization"
                ] = `Bearer ${res.data.data.attributes.access_token}`;

                commit("setUser", res.data);
                dispatch("fetchProjects");

                $router.push($route.query.redirect || "/");
            })
            .catch(err => console.log(err.data))
            .finally(commit("setLoadingLogin", false));
    },
    logout({ commit }) {
        commit("unsetUser");
    },

    setUser({ dispatch }, data) {
        commit("setUser", data);
    }
};

const mutations = {
    setUser(state, value) {
        state.user = value;
        sessionStorage.setItem(state.sessionKey, JSON.stringify(value));
    },
    unsetUser(state) {
        state.user = null;
        sessionStorage.removeItem(state.sessionKey);
    },

    setLoadingLogin(state, value) {
        state.loadingLogin = value;
    }
};

export default {
    state,
    getters,
    actions,
    mutations
};
