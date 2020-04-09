<template>
    <v-card v-if="fields">
        <v-card-title>
            <p>Campos para detalhamento de Projetos</p>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-data-table
                disable-pagination
                hide-default-footer
                :headers="headers"
                :items="fields.data"
                sort-by="name"
            >
                <template v-slot:top>
                    <v-toolbar flat color="transparent">
                        <v-dialog v-model="dialog" scrollable max-width="700px">
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    absolute
                                    right
                                    color="primary"
                                    outlined
                                    small
                                    dark
                                    v-on="on"
                                    >Criar novo campo</v-btn
                                >
                            </template>
                            <v-card>
                                <v-card-title>
                                    <span class="headline">{{
                                        formTitle
                                    }}</span>
                                </v-card-title>

                                <v-card-text>
                                    <v-container>
                                        <v-row justify="center" align="center">
                                            <v-col cols="12" sm="10">
                                                <v-select
                                                    v-if="editedIndex < 0"
                                                    v-model="editedItem.type"
                                                    :items="fieldTypesList"
                                                    label="Formato do dado"
                                                    placeholder="Selecione um dos formatos"
                                                    outlined
                                                    required
                                                    :error-messages="typeErrors"
                                                    @input="
                                                        $v.editedItem.type.$touch()
                                                    "
                                                    @blur="
                                                        $v.editedItem.type.$touch()
                                                    "
                                                ></v-select>
                                            </v-col>
                                            <v-col cols="12" sm="10">
                                                <v-text-field
                                                    v-model="editedItem.name"
                                                    :counter="50"
                                                    :error-messages="nameErrors"
                                                    label="Nome"
                                                    required
                                                    @input="
                                                        $v.editedItem.name.$touch()
                                                    "
                                                    @blur="
                                                        $v.editedItem.name.$touch()
                                                    "
                                                ></v-text-field>
                                            </v-col>
                                            <v-col cols="12" sm="10">
                                                <v-textarea
                                                    v-model="
                                                        editedItem.description
                                                    "
                                                    hint="Informe uma breve descrição deste projeto"
                                                    :error-messages="
                                                        descriptionErrors
                                                    "
                                                    label="Descrição do projeto"
                                                    :counter="255"
                                                    outlined
                                                    required
                                                    @input="
                                                        $v.editedItem.description.$touch()
                                                    "
                                                    @blur="
                                                        $v.editedItem.description.$touch()
                                                    "
                                                ></v-textarea>
                                            </v-col>

                                            <v-col cols="12" sm="10">
                                                <v-select
                                                    v-model="editedItem.mask"
                                                    :items="fieldMaskList"
                                                    label="Máscara para numero numero"
                                                    placeholder="Selecione um formato de máscara"
                                                    outlined
                                                    required
                                                ></v-select>
                                            </v-col>
                                            <v-col cols="12" sm="10">
                                                <v-row>
                                                    <v-col cols="6">
                                                        <minField
                                                            label="Quantidade mínima de caracteres"
                                                            :value.sync="
                                                                editedItem.min
                                                            "
                                                        />
                                                    </v-col>
                                                    <v-col cols="6">
                                                        <maxField
                                                            label="Quantidade máxima de caracteres"
                                                            :value.sync="
                                                                editedItem.max
                                                            "
                                                        />
                                                    </v-col>
                                                </v-row>
                                            </v-col>
                                        </v-row>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="close"
                                        >Cancelar</v-btn
                                    >
                                    <v-btn
                                        color="blue darken-1"
                                        text
                                        @click="save"
                                        >Salvar</v-btn
                                    >
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </v-toolbar>
                </template>
                <template v-slot:item.name="{ item }">{{
                    item.data.attributes.name
                }}</template>
                <template v-slot:item.description="{ item }">{{
                    item.data.attributes.description
                }}</template>
                <template v-slot:item.min="{ item }">{{
                    item.data.attributes.min
                }}</template>
                <template v-slot:item.max="{ item }">{{
                    item.data.attributes.max
                }}</template>
                <template v-slot:item.mask="{ item }">{{
                    item.data.attributes.mask
                }}</template>
                <template v-slot:item.active="{ item }">
                    <span v-if="item.data.attributes.active">Sim</span>
                    <span v-else>Não</span>
                </template>
                <template v-slot:item.actions="{ item }">
                    <v-icon
                        small
                        class="mr-2"
                        @click="editItem(item)"
                        color="primary"
                        >mdi-pencil</v-icon
                    >
                    <v-icon
                        v-if="item.data.attributes.active"
                        color="red"
                        small
                        @click="deleteItem(item)"
                        >mdi-delete</v-icon
                    >
                    <v-icon v-else color="green" small @click="deleteItem(item)"
                        >mdi-recycle</v-icon
                    >
                </template>
                <template v-slot:no-data>
                    <p>Sem informações cadastradas</p>
                </template>
            </v-data-table>
        </v-card-text>
    </v-card>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
import { mapGetters } from "vuex";
import minField from "../components/formInputs/IntegerField";
import maxField from "../components/formInputs/IntegerField";

export default {
    name: "Fields",
    components: {
        minField,
        maxField
    },
    mixins: [validationMixin],
    validations: {
        editedItem: {
            name: {
                required,
                minLength: minLength(3),
                maxLength: maxLength(50)
            },
            description: {
                required,
                minLength: minLength(3),
                maxLength: maxLength(255)
            },
            type: {
                required
            }
        }
    },
    created() {
        this.$store.dispatch("fetchFields");
    },
    data: () => ({
        dialog: false,
        editedIndex: -1,
        maskTypes: [
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
            },
            {
                name: "Personalizado",
                format: ""
            }
        ],
        fieldTypes: [
            {
                type: "integerField",
                name: "Número inteiro",
                description: "Recebe apenas números inteiros. ex: 10"
            },
            {
                type: "numberField",
                name: "Número decimal",
                description:
                    "Recebe apenas números com parte de fração. ex: 10,00"
            },
            {
                type: "dateField",
                name: "Data",
                description: "Recebe datas no formato brasieiro. ex: 10/04/2020"
            },
            {
                type: "textField",
                name: "Texto pequeno",
                description: "Útil para textos com no máximo 255 caracteres"
            },
            {
                type: "largeTextField",
                name: "Texto grande",
                description: "Útil para textos com mais 255 caracteres"
            }
        ],
        editedItem: {
            id: "",
            name: "",
            type: "",
            description: "",
            active: true
        },
        defaultItem: {
            name: "",
            type: "",
            description: "",
            active: true
        }
    }),

    computed: {
        ...mapGetters(["fields"]),
        fieldMaskList() {
            var list = [];
            this.maskTypes.forEach(item => list.push(item.name));
            return list;
        },
        fieldTypesList() {
            var list = [];
            this.fieldTypes.forEach(item => list.push(item.name));
            return list;
        },
        fieldTypeSelected() {
            return this.fieldTypes.find(
                item => item.name == this.editedItem.type
            ).type;
        },
        fieldMaskSelected() {
            if (!this.editedItem.mask) return null;

            return this.maskTypes.find(
                item => item.name == this.editedItem.mask
            ).format;
        },
        headers() {
            var headers = [
                {
                    text: "Campo",
                    align: "start",
                    value: "name"
                },
                {
                    text: "Descrição",
                    value: "description"
                },
                {
                    text: "Tamanho mínimo",
                    value: "min"
                },
                {
                    text: "Tamanho máximo",
                    value: "max"
                },
                {
                    text: "Máscara",
                    value: "mask"
                },
                {
                    text: "Ativo",
                    value: "active"
                },
                {
                    text: "Ações",
                    value: "actions"
                }
            ];
            if (this.hasSession) {
                headers.push({
                    text: "Ações",
                    value: "actions",
                    sortable: false
                });
            }

            return headers;
        },

        formTitle() {
            return this.editedIndex === -1 ? "Novo Item" : "Edição de Item";
        },
        nameErrors() {
            const errors = [];
            if (!this.$v.editedItem.name.$dirty) return errors;
            !this.$v.editedItem.name.required &&
                errors.push("Campo Obrigatório.");
            !this.$v.editedItem.name.minLength &&
                errors.push("Mínimo de 3 caracteres");
            !this.$v.editedItem.name.maxLength &&
                errors.push("Máximo de 50 caracteres");
            return errors;
        },
        descriptionErrors() {
            const errors = [];
            if (!this.$v.editedItem.description.$dirty) return errors;
            !this.$v.editedItem.description.required &&
                errors.push("Campo Obrigatório.");
            !this.$v.editedItem.description.minLength &&
                errors.push("Mínimo de 3 caracteres");
            !this.$v.editedItem.description.maxLength &&
                errors.push("Máximo de 255 caracteres");
            return errors;
        },
        typeErrors() {
            const errors = [];
            if (!this.$v.editedItem.type.$dirty) return errors;
            !this.$v.editedItem.type.required &&
                errors.push("Campo Obrigatório.");
            return errors;
        }
    },

    watch: {
        dialog(val) {
            val || this.close();
        }
    },

    methods: {
        editItem(item) {
            this.editedIndex = this.fields.data.indexOf(item);
            this.editedItem = Object.assign({}, item.data.attributes);
            this.editedItem.id = item.data.id;
            this.dialog = true;
        },

        deleteItem(item) {
            if (confirm("Deseja realmente alterar o status este atributo?")) {
                this.$store.dispatch("updateField", {
                    id: item.data.id,
                    name: item.data.attributes.name,
                    description: item.data.attributes.description,
                    active: !item.data.attributes.active
                });
            }
        },

        close() {
            this.dialog = false;
            this.$v.$reset();
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            }, 300);
        },

        save() {
            if (this.editedIndex > -1) {
                this.$store.dispatch("updateField", {
                    id: this.editedItem.id,
                    name: this.editedItem.name,
                    description: this.editedItem.description,
                    max: this.editedItem.max,
                    min: this.editedItem.min,
                    mask: this.fieldMaskSelected,
                    active: this.editedItem.active
                });
            } else {
                this.$store.dispatch("createField", {
                    name: this.editedItem.name,
                    description: this.editedItem.description,
                    max: this.editedItem.max,
                    min: this.editedItem.min,
                    mask: this.fieldMaskSelected,
                    type: this.fieldTypeSelected
                });
            }
            this.close();
        }
    }
};
</script>

<style></style>
