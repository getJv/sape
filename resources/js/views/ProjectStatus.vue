<template>
  <v-card v-if="projectStatuses">
    <v-card-title>
      <p>Fases de Projeto</p>
    </v-card-title>
    <v-divider></v-divider>
    <v-card-text>
      <v-data-table
        disable-pagination
        hide-default-footer
        :headers="headers"
        :items="projectStatuses.data"
        sort-by="name"
      >
        <template v-slot:top>
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
                >Incluir nova Fase</v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row justify="center" align="center">
                      <v-col cols="12" sm="10">
                        <v-text-field
                          v-model="editedItem.name"
                          :counter="50"
                          :error-messages="nameErrors"
                          label="Nome"
                          required
                          @input="$v.editedItem.name.$touch()"
                          @blur="$v.editedItem.name.$touch()"
                        ></v-text-field>
                      </v-col>
                      <v-col cols="12" sm="10">
                        <v-textarea
                          v-model="editedItem.description"
                          hint="Informe uma breve descrição deste projeto"
                          :error-messages="descriptionErrors"
                          label="Descrição do projeto"
                          :counter="255"
                          outlined
                          required
                          @input="$v.editedItem.description.$touch()"
                          @blur="$v.editedItem.description.$touch()"
                        ></v-textarea>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
                  <v-btn color="blue darken-1" text @click="save">Salvar</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.name="{ item }">{{item.data.attributes.name}}</template>
        <template v-slot:item.description="{ item }">{{item.data.attributes.description}}</template>
        <template v-slot:item.active="{ item }">
          <span v-if="item.data.attributes.active">Sim</span>
          <span v-else>Não</span>
        </template>
        <template v-slot:item.actions="{ item }">
          <v-icon small class="mr-2" @click="editItem(item)" color="primary">mdi-pencil</v-icon>
          <v-icon
            v-if="item.data.attributes.active"
            color="red"
            small
            @click="deleteItem(item)"
          >mdi-delete</v-icon>
          <v-icon v-else color="green" small @click="deleteItem(item)">mdi-recycle</v-icon>
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

export default {
  name: "ProjectStatus",
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
        maxLength: maxLength(100)
      }
    }
  },
  created() {
    this.$store.dispatch("fetchProjectStatuses");
  },
  data: () => ({
    dialog: false,
    editedIndex: -1,
    editedItem: {
      id: "",
      name: "",
      description: "",
      active: true
    },
    defaultItem: {
      name: "",
      description: "",
      active: true
    }
  }),

  computed: {
    ...mapGetters(["projectStatuses"]),
    headers() {
      var headers = [
        {
          text: "Fase",
          align: "start",
          value: "name"
        },
        {
          text: "Descrição",
          value: "description"
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
      this.editedIndex = this.projectStatuses.data.indexOf(item);
      this.editedItem = Object.assign({}, item.data.attributes);
      this.editedItem.id = item.data.id;
      this.dialog = true;
    },

    deleteItem(item) {
      if (confirm("Deseja realmente alterar o status este atributo?")) {
        this.$store.dispatch("updateProjectStatus", {
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
        this.$store.dispatch("updateProjectStatus", {
          id: this.editedItem.id,
          name: this.editedItem.name,
          description: this.editedItem.description,
          active: this.editedItem.active
        });
      } else {
        this.$store.dispatch("createProjectStatus", {
          name: this.editedItem.name,
          description: this.editedItem.description
        });
      }
      this.close();
    }
  }
};
</script>

<style>
</style>
