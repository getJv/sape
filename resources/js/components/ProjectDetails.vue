<template>
    <div if="project">
        <v-data-table
            disable-pagination
            hide-default-footer
            hide-default-header
            :headers="headers"
            :items="projectFields.data"
            sort-by="name"
        >
            <template v-if="hasSession" v-slot:top>
                <v-toolbar flat color="transparent">
                    <v-dialog v-model="dialog" max-width="700px">
                        <template v-slot:activator="{ on }">
                            <p class="body-1 font-weight-bold">
                                Características do projeto
                                <v-icon
                                    absolute
                                    right
                                    color="primary"
                                    outlined
                                    small
                                    dark
                                    v-on="on"
                                    >mdi-table-column-plus-after</v-icon
                                >
                            </p>
                        </template>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                            </v-card-title>

                            <v-card-text>
                                <v-container>
                                    <!-- Vinculo de Campos -->
                                    <v-row
                                        v-if="editedIndex < 0"
                                        justify="center"
                                        align="center"
                                    >
                                        <v-col cols="12">
                                            <v-select
                                                v-if="fields"
                                                v-model="editedItem.fieldName"
                                                :items="fieldsList"
                                                label="Selecione um Campo"
                                                placeholder="Lista de campos disponíveis"
                                                outlined
                                                required
                                            >
                                                <template v-slot:no-data>
                                                    <p>
                                                        Sem informações
                                                        cadastradas
                                                    </p>
                                                </template>
                                            </v-select>
                                            <v-spacer></v-spacer>
                                            <v-btn
                                                right
                                                absolute
                                                outlined
                                                small
                                                color="blue darken-1"
                                                @click="addField()"
                                                >Vincular Campo</v-btn
                                            >
                                        </v-col>
                                        <v-col cols="12">
                                            <v-data-table
                                                disable-pagination
                                                hide-default-footer
                                                :headers="headers"
                                                :items="projectFields.data"
                                                sort-by="name"
                                            >
                                                <template
                                                    v-slot:item.name="{
                                                        item
                                                    }"
                                                    >{{
                                                        item.links.field.data
                                                            .attributes.name
                                                    }}</template
                                                >
                                                <template
                                                    v-slot:item.value="{
                                                        item
                                                    }"
                                                >
                                                    <span
                                                        v-if="
                                                            item.links.field
                                                                .data.attributes
                                                                .active
                                                        "
                                                        >Vínculo ativo</span
                                                    >
                                                    <span v-else
                                                        >Vínculo inativo</span
                                                    >
                                                </template>
                                                <template
                                                    v-slot:item.actions="{
                                                        item
                                                    }"
                                                >
                                                    <v-icon
                                                        v-if="
                                                            item.data.attributes
                                                                .active
                                                        "
                                                        color="red"
                                                        small
                                                        @click="
                                                            deleteItem(item)
                                                        "
                                                        >mdi-delete</v-icon
                                                    >
                                                    <v-icon
                                                        v-else
                                                        color="green"
                                                        small
                                                        @click="
                                                            deleteItem(item)
                                                        "
                                                        >mdi-recycle</v-icon
                                                    >
                                                </template>
                                                <template v-slot:no-data>
                                                    <p>
                                                        Sem informações
                                                        cadastradas
                                                    </p>
                                                </template>
                                            </v-data-table>
                                        </v-col>
                                    </v-row>
                                    <!-- Edicaçõ do valor dos campos -->
                                    <v-row
                                        v-else
                                        justify="center"
                                        align="center"
                                    >
                                        <v-col cols="12" sm="10">
                                            <!-- :required="editedItem.required" CRIAR NO SISTEMA/TABELA -->
                                            <component
                                                :is="editedItem.type"
                                                :ref="editedItem.hashName"
                                                :label="editedItem.fieldName"
                                                :min="editedItem.min"
                                                :max="editedItem.max"
                                                :mask="editedItem.mask"
                                                :value.sync="editedItem.value"
                                            />
                                        </v-col>
                                    </v-row>
                                </v-container>
                            </v-card-text>

                            <v-card-actions>
                                <v-spacer></v-spacer>
                                <v-btn color="blue darken-1" text @click="close"
                                    >Cancelar</v-btn
                                >
                                <div v-if="$refs[editedItem.hashName]">
                                    <v-btn
                                        v-if="
                                            editedIndex > -1 &&
                                                !$refs[editedItem.hashName].$v
                                                    .$invalid
                                        "
                                        color="blue darken-1"
                                        text
                                        @click="save"
                                        >Salvar</v-btn
                                    >
                                </div>
                            </v-card-actions>
                        </v-card>
                    </v-dialog>
                </v-toolbar>
            </template>
            <template v-slot:item.name="{ item }">{{
                item.links.field.data.attributes.name
            }}</template>
            <template v-slot:item.value="{ item }">
                <span v-if="item.data.attributes.value">{{
                    item.data.attributes.value
                }}</span>
                <span v-else>- sem informação -</span>
            </template>
            <template v-slot:item.actions="{ item }">
                <v-icon small class="mr-2" @click="editItem(item)"
                    >mdi-pencil</v-icon
                >
            </template>
            <template v-slot:no-data>
                <p>Sem informações cadastradas</p>
            </template>
        </v-data-table>
    </div>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
import { mapGetters } from "vuex";
import integerField from "./formInputs/IntegerField";
import numberField from "./formInputs/NumberField";
import textField from "./formInputs/TextField";
import dateField from "./formInputs/DatePicker";
import textAreaField from "./formInputs/TextAreaField";

export default {
    name: "ProjectDetails",
    props: ["hasSession"],
    components: {
        integerField,
        numberField,
        textField,
        dateField,
        textAreaField
    },
    mixins: [validationMixin],
    validations: {
        editedItem: {
            value: {
                required
            }
        }
    },

    data: () => ({
        dialog: false,
        editedIndex: -1,
        editedItem: {
            id: "",
            fieldName: "",
            value: ""
        },
        defaultItem: {
            fieldName: "",
            value: ""
        }
    }),
    created() {
        this.$store.dispatch("fetchFields");
    },

    computed: {
        ...mapGetters(["project", "projectFields", "fields"]),
        fieldsList() {
            var list = [];
            this.fields.data.forEach(item =>
                list.push(item.data.attributes.name)
            );
            return list;
        },
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
            return this.editedIndex === -1
                ? "Gerenciar campos vinculados"
                : "Alteração do campo: " + this.editedItem.fieldName;
        },
        valueErrors() {
            const errors = [];
            if (!this.$v.editedItem.value.$dirty) return errors;
            !this.$v.editedItem.value.required &&
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
        addField() {
            var field = this.fields.data.find(
                item => item.data.attributes.name == this.editedItem.fieldName
            );

            this.$store.dispatch("createProjectField", {
                project_id: this.project.data.id,
                field_id: field.data.id
            });
            this.close();
        },

        editItem(item) {
            this.editedIndex = this.projectFields.data.indexOf(item);
            this.editedItem = Object.assign({}, item.data.attributes);
            this.editedItem.id = item.data.id;
            this.editedItem.fieldName = item.links.field.data.attributes.name;
            this.editedItem.project_field_id = item.data.id;
            this.editedItem.type = item.links.field.data.attributes.type;
            this.editedItem.min = item.links.field.data.attributes.min;
            this.editedItem.max = item.links.field.data.attributes.max;
            this.editedItem.mask = item.links.field.data.attributes.mask;
            this.editedItem.hashName = this.editedItem.fieldName
                .replace(/ /g, "_")
                .toLowerCase();
            this.dialog = true;
        },

        deleteItem(item) {
            if (
                confirm(
                    "Deseja realmente alterar o vínculo deste atributo com o projeto?"
                )
            ) {
                this.$store.dispatch("updateProjectField", {
                    project_field_id: item.data.id,
                    project_id: item.data.attributes.project_id,
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
                this.$store.dispatch("updateProjectField", {
                    project_field_id: this.editedItem.project_field_id,
                    project_id: this.editedItem.project_id,
                    value: this.editedItem.value,
                    active: this.editedItem.active
                });
            }
            this.close();
        }
    }
};
</script>

<style></style>
