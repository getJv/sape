<template>
  <v-dialog v-model="dialog" persistent max-width="700px">
    <template v-slot:activator="{ on }">
      <v-icon color="primary" hint="Adicionar atividade" dark v-on="on">mdi-calendar-plus</v-icon>
    </template>
    <v-card>
      <v-card-title class="headline">Nova atividade para a fase #{{step_id}}</v-card-title>
      <v-card-text>
        <v-container>
          <v-row justify="center" align="center">
            <v-col cols="12" sm="10">
              <v-text-field
                :value="eventName"
                :error-messages="eventNameErrors"
                label="Nome da atividade"
                :counter="100"
                required
                @input="$v.eventName.$touch()"
                @blur="$v.eventName.$touch()"
              />
            </v-col>
            <v-col cols="12" sm="10">
              <v-textarea
                :value="eventDescription"
                hint="Informe uma breve descrição deste projeto"
                :error-messages="eventDescriptionErrors"
                label="Descrição do projeto"
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
        <v-btn color="primary" text @click="dialog = false">Cancelar</v-btn>
        <v-btn color="primary" outlined @click="dialog = false">Salvar</v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, minLength, maxLength } from "vuelidate/lib/validators";

export default {
  name: "BtnEditProject",
  mixins: [validationMixin],
  validations: {
    eventDescription: {
      required,
      minLength: minLength(21),
      maxLength: maxLength(255)
    },
    eventName: {
      required,
      minLength: minLength(21),
      maxLength: maxLength(100)
    }
  },
  props: ["hasSession", "step_id"],
  data() {
    return {
      eventName: "",
      eventDescription: "",
      dialog: false
    };
  },
  computed: {
    eventNameErrors() {
      const errors = [];
      if (!this.$v.eventName.$dirty) return errors;
      !this.$v.eventName.required && errors.push("Campo Obrigatório.");
      !this.$v.eventName.minLength && errors.push("Mínimo de 50 caracteres");
      !this.$v.eventName.maxLength && errors.push("Máximo de 100 caracteres");
      return errors;
    },
    eventDescriptionErrors() {
      const errors = [];
      if (!this.$v.eventDescription.$dirty) return errors;
      !this.$v.eventDescription.required && errors.push("Campo Obrigatório.");
      !this.$v.eventDescription.minLength &&
        errors.push("Mínimo de 50 caracteres");
      !this.$v.eventDescription.maxLength &&
        errors.push("Máximo de 255 caracteres");
      return errors;
    }
  }
};
</script>

<style></style>
