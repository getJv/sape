const state = {
    loadingField: false,
    fields: null,
    selectedField: null,
    field: null,
    maskTypes: [
        {
            name: "Sem máscara",
            format: ""
        },
        {
            name: "Data simples",
            format: "##/##/####"
        },
        {
            name: "CEP",
            format: "#####-###"
        },
        {
            name: "CPF",
            format: "###.###.###-##"
        },
        {
            name: "CNPJ",
            format: "###.###.###/####-##"
        },
        {
            name: "Hora",
            format: "##:##:##"
        }
    ],
    fieldTypes: [
        {
            type: "integerField",
            name: "Número inteiro",
            description: "Recebe apenas números inteiros. ex: 10",
            fields: ["mask", "min-max"]
        },
        {
            type: "numberField",
            name: "Número decimal",
            description: "Recebe apenas números com parte de fração. ex: 10,00",
            fields: ["min-max"]
        },
        {
            type: "dateField",
            name: "Data",
            description: "Recebe datas no formato brasieiro. ex: 10/04/2020",
            fields: []
        },
        {
            type: "textField",
            name: "Texto pequeno",
            description: "Útil para textos com no máximo 255 caracteres",
            fields: ["min-max"]
        },
        {
            type: "textAreaField",
            name: "Texto grande",
            description: "Útil para textos com mais 255 caracteres",
            fields: ["min-max"]
        },
        {
            type: "enumField",
            name: "Lista de opções",
            description: "Útil definir valores fixos de escolha.",
            fields: ["enum"]
        }
    ]
};

const getters = {
    field: state => state.field,
    fields: state => state.fields,
    loadingField: state => state.loadingField,
    maskTypes: state => state.maskTypes,
    fieldTypes: state => state.fieldTypes
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
                items: data.items,
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
