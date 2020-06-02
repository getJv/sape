<template>
    <v-dialog v-model="dialog" persistent max-width="700px">
        <template v-slot:activator="{ on }">
            <v-icon color="primary" outlined small dark v-on="on"
                >mdi-pencil</v-icon
            >
        </template>
        <v-card>
            <v-card-title class="headline"
                >Edição do Evento #{{ item.data.id }}</v-card-title
            >
            <v-card-text>
                <v-container>
                    <v-row justify="center" align="center">
                        <v-col cols="12" sm="10">
                            <v-text-field
                                v-model="eventName"
                                :error-messages="eventNameErrors"
                                label="Nome do Projeto"
                                :counter="100"
                                required
                                @input="$v.eventName.$touch()"
                                @blur="$v.eventName.$touch()"
                            />
                        </v-col>
                        <v-col cols="12" sm="10">
                            <v-textarea
                                v-model="eventDescription"
                                hint="Informe uma breve descrição deste projeto"
                                :error-messages="eventDescriptionErrors"
                                label="Descrição do evento"
                                :counter="255"
                                outlined
                                required
                                @input="$v.eventDescription.$touch()"
                                @blur="$v.eventDescription.$touch()"
                            ></v-textarea>
                        </v-col>
                    </v-row>
                </v-container>
            </v-card-text>
            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="primary" text @click="cancelar()">Cancelar</v-btn>
                <v-btn color="primary" outlined @click="atualizar()"
                    >Atualizar</v-btn
                >
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
    name: "BtnEditEvent",
    props: {
        item: {
            required: true,
            type: Object
        }
    },

    mixins: [validationMixin],
    validations: {
        eventDescription: {
            required,
            minLength: minLength(3),
            maxLength: maxLength(255)
        },
        eventName: {
            required,
            minLength: minLength(3),
            maxLength: maxLength(100)
        }
    },

    methods: {
        atualizar() {
            this.$store.dispatch("updateEvent", {
                id: this.item.data.id,
                name: this.eventName,
                description: this.eventDescription,
                project_workflow_id: this.item.data.attributes
                    .project_workflow_id,
                owner_id: this.item.data.attributes.owner_id,
                active: this.item.data.attributes.active
            });
            this.dialog = false;
        },
        cancelar() {
            this.dialog = false;
        }
    },

    data() {
        return {
            dialog: false,
            reset_data: ""
        };
    },
    computed: {
        eventDescription: {
            get: function() {
                return this.item.data.attributes.description;
            },
            set: function(value) {
                this.item.data.attributes.description = value;
            }
        },
        eventName: {
            get: function() {
                return this.item.data.attributes.name;
            },
            set: function(value) {
                this.item.data.attributes.name = value;
            }
        },

        eventNameErrors() {
            const errors = [];
            if (!this.$v.eventName.$dirty) return errors;
            !this.$v.eventName.required && errors.push("Campo Obrigatório.");
            !this.$v.eventName.minLength &&
                errors.push("Mínimo de 3 caracteres");
            !this.$v.eventName.maxLength &&
                errors.push("Máximo de 100 caracteres");
            return errors;
        },

        eventDescriptionErrors() {
            const errors = [];
            if (!this.$v.eventDescription.$dirty) return errors;
            !this.$v.eventDescription.required &&
                errors.push("Campo Obrigatório.");
            !this.$v.eventDescription.minLength &&
                errors.push("Mínimo de 3 caracteres");
            !this.$v.eventDescription.maxLength &&
                errors.push("Máximo de 255 caracteres");
            return errors;
        }
    }
};
</script>

<style></style>
