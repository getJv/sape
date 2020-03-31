<template>
  <v-dialog v-model="dialog" persistent max-width="700px">
    <template v-slot:activator="{ on }">
      <v-icon small color="primary" hint="Adicionar atividade" v-on="on" dark>mdi-toy-brick-plus</v-icon>
    </template>

    <v-card>
      <v-card-title class="headline">Novo do Projeto</v-card-title>
      <v-card-text>
        <v-container>
          <v-row justify="center" align="center">
            <v-col cols="12" sm="10">
              <v-text-field
                v-model="projectName"
                :error-messages="projectNameErrors"
                label="Nome do Projeto"
                :counter="100"
                required
                @input="$v.projectName.$touch()"
                @blur="$v.projectName.$touch()"
              />
            </v-col>
            <v-col cols="12" sm="10">
              <v-textarea
                v-model="projectDescription"
                hint="Informe uma breve descrição deste projeto"
                :error-messages="projectDescriptionErrors"
                label="Descrição do projeto"
                :counter="255"
                outlined
                required
                @input="$v.projectDescription.$touch()"
                @blur="$v.projectDescription.$touch()"
              ></v-textarea>
            </v-col>
          </v-row>
        </v-container>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="primary" text @click="dialog = false">Cancelar</v-btn>
        <v-btn color="primary" outlined @click="registrar">Salvar</v-btn>
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
    projectDescription: {
      required,
      minLength: minLength(21),
      maxLength: maxLength(255)
    },
    projectName: {
      required,
      minLength: minLength(21),
      maxLength: maxLength(100)
    }
  },
  props: ["hasSession"],
  methods: {
    registrar() {
      axios
        .post("/api/projects", {
          name: this.projectName,
          description: this.projectDescription
        })
        .then(res => console.log(res.data))
        .catch(res => console.log(res.data))
        .finally((this.dialog = false));
    }
  },
  data() {
    return {
      projectName: "",
      projectDescription: "",
      dialog: false
    };
  },
  computed: {
    projectNameErrors() {
      const errors = [];
      if (!this.$v.projectName.$dirty) return errors;
      !this.$v.projectName.required && errors.push("Campo Obrigatório.");
      !this.$v.projectName.minLength && errors.push("Mínimo de 21 caracteres");
      !this.$v.projectName.maxLength && errors.push("Máximo de 100 caracteres");
      return errors;
    },
    projectDescriptionErrors() {
      const errors = [];
      if (!this.$v.projectDescription.$dirty) return errors;
      !this.$v.projectDescription.required && errors.push("Campo Obrigatório.");
      !this.$v.projectDescription.minLength &&
        errors.push("Mínimo de 21 caracteres");
      !this.$v.projectDescription.maxLength &&
        errors.push("Máximo de 255 caracteres");
      return errors;
    }
  }
};
</script>

<style></style>
