<template>
    <v-card>
        <v-card-title>
            <p>Características do projeto</p>
        </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-data-table
                disable-pagination
                hide-default-footer
                hide-default-header
                :headers="headers"
                :items="items"
                sort-by="name"
            >
                <template v-if="hasSession" v-slot:top>
                    <v-toolbar flat color="transparent">
                        <v-dialog v-model="dialog" max-width="500px">
                            <template v-slot:activator="{ on }">
                                <v-btn
                                    absolute
                                    right
                                    color="primary"
                                    outlined
                                    small
                                    dark
                                    v-on="on"
                                    >Novo Item</v-btn
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
                                                <v-text-field
                                                    :value="editedItem.name"
                                                    :counter="100"
                                                    :error-messages="itemErrors"
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
                                                <v-text-field
                                                    v-model="editedItem.value"
                                                    :error-messages="
                                                        valueErrors
                                                    "
                                                    :counter="100"
                                                    label="Valor"
                                                    @input="
                                                        $v.editedItem.value.$touch()
                                                    "
                                                    @blur="
                                                        $v.editedItem.value.$touch()
                                                    "
                                                ></v-text-field>
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
                <template v-slot:item.actions="{ item }">
                    <v-icon small class="mr-2" @click="editItem(item)">
                        mdi-pencil
                    </v-icon>
                    <v-icon small @click="deleteItem(item)">
                        mdi-delete
                    </v-icon>
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
export default {
    name: "ProjectDetails",
    props: ["items", "hasSession"],
    mixins: [validationMixin],
    validations: {
        editedItem: {
            name: {
                required,
                minLength: minLength(3),
                maxLength: maxLength(100)
            },
            value: {
                required,
                minLength: minLength(3),
                maxLength: maxLength(100)
            }
        }
    },
    data: () => ({
        dialog: false,
        editedIndex: -1,
        editedItem: {
            name: "",
            value: ""
        },
        defaultItem: {
            name: "",
            value: ""
        }
    }),

    computed: {
        headers() {
            var headers = [
                {
                    text: "Item",
                    align: "start",
                    value: "name"
                },
                {
                    text: "Valor",
                    value: "value"
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
        itemErrors() {
            const errors = [];
            if (!this.$v.editedItem.name.$dirty) return errors;
            !this.$v.editedItem.name.required &&
                errors.push("Campo Obrigatório.");
            !this.$v.editedItem.name.minLength &&
                errors.push("Mínimo de 3 caracteres");
            !this.$v.editedItem.name.maxLength &&
                errors.push("Máximo de 100 caracteres");
            return errors;
        },
        valueErrors() {
            const errors = [];
            if (!this.$v.editedItem.value.$dirty) return errors;
            !this.$v.editedItem.value.required &&
                errors.push("Campo Obrigatório.");
            !this.$v.editedItem.value.minLength &&
                errors.push("Mínimo de 3 caracteres");
            !this.$v.editedItem.value.maxLength &&
                errors.push("Máximo de 100 caracteres");
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
            this.editedIndex = this.items.indexOf(item);
            this.editedItem = Object.assign({}, item);
            this.dialog = true;
        },

        deleteItem(item) {
            const index = this.items.indexOf(item);
            confirm("Deseja realmente excluir este atributo?") &&
                this.items.splice(index, 1);
        },

        close() {
            this.dialog = false;
            setTimeout(() => {
                this.editedItem = Object.assign({}, this.defaultItem);
                this.editedIndex = -1;
            }, 300);
        },

        save() {
            if (this.editedIndex > -1) {
                Object.assign(this.items[this.editedIndex], this.editedItem);
            } else {
                this.items.push(this.editedItem);
            }
            this.close();
        }
    }
};
</script>

<style></style>
