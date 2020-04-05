<template>
  <v-card v-if="projectStatuses">
    <v-card-title>
      <p>Gerenciar Workflow de Projeto</p>
    </v-card-title>
    <v-divider></v-divider>
    <v-card-text>
      <v-select
        v-if="projects"
        v-model="selectedProject"
        :items="projectList"
        label="Selecione um projeto"
        placeholder="Lista de projetos"
        outlined
        required
      ></v-select>

      <v-data-table
        v-if="selectedProject"
        disable-pagination
        hide-default-footer
        :headers="headers"
        :items="projectWorkflow.data"
        sort-by="order"
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
                >Incluir nova transição</v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row justify="center" align="center">
                      <v-col cols="12" sm="10">
                        <v-select
                          v-if="originStatusList"
                          v-model="editedItem.origin"
                          :items="originStatusList"
                          label="Origem"
                          placeholder="Selecione o início da transição"
                          outlined
                          required
                          :error-messages="originErrors"
                          @input="$v.editedItem.origin.$touch()"
                          @blur="$v.editedItem.origin.$touch()"
                        ></v-select>
                      </v-col>
                      <v-col cols="12" sm="10">
                        <v-select
                          v-if="editedItem.origin"
                          v-model="editedItem.destiny"
                          :items="destinyStatusList"
                          label="Destido"
                          placeholder="Selecione o destino da transição"
                          outlined
                          required
                          :error-messages="destinyErrors"
                          @input="$v.editedItem.destiny.$touch()"
                          @blur="$v.editedItem.destiny.$touch()"
                        ></v-select>
                      </v-col>
                    </v-row>
                  </v-container>
                </v-card-text>
                <v-card-actions>
                  <v-spacer></v-spacer>
                  <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
                  <v-btn color="blue darken-1" text v-if="editedItem.destiny" @click="save">Salvar</v-btn>
                </v-card-actions>
              </v-card>
            </v-dialog>
          </v-toolbar>
        </template>
        <template v-slot:item.order="{ item }">{{item.data.attributes.order}}</template>
        <template v-slot:item.origin="{ item }">{{item.links.old_status.data.attributes.name}}</template>
        <template v-slot:item.destiny="{ item }">{{item.links.new_status.data.attributes.name}}</template>
        <template v-slot:item.actions="{ item }">
          <v-icon color="red" small @click="deleteItem(item)">mdi-delete</v-icon>
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
  name: "ProjectWorkflow",
  mixins: [validationMixin],
  validations: {
    editedItem: {
      origin: {
        required
      },
      destiny: {
        required
      }
    }
  },
  created() {
    this.$store.dispatch("fetchProjectStatuses");
  },

  data: () => ({
    dialog: false,
    selectedProject: "",
    editedIndex: -1,
    editedItem: {
      origin: "",
      destiny: ""
    },
    defaultItem: {
      origin: "",
      destiny: ""
    }
  }),

  computed: {
    projectList() {
      var list = [];
      this.projects.data.forEach(item => list.push(item.data.attributes.name));
      return list;
    },
    originStatusList() {
      var list = [];
      this.projectStatuses.data.forEach(item =>
        list.push(item.data.attributes.name)
      );
      return list;
    },
    destinyStatusList() {
      var list = [];
      this.projectStatuses.data.forEach(item => {
        if (item.data.attributes.name != this.editedItem.origin)
          list.push(item.data.attributes.name);
      });
      return list;
    },

    ...mapGetters(["projects", "projectStatuses", "projectWorkflow"]),
    headers() {
      var headers = [
        {
          text: "Ordem",
          align: "start",
          value: "order"
        },
        {
          text: "Origem",
          value: "origin"
        },
        {
          text: "Destino",
          value: "destiny"
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
    originErrors() {
      const errors = [];
      if (!this.$v.editedItem.origin.$dirty) return errors;
      !this.$v.editedItem.origin.required && errors.push("Campo Obrigatório.");
      return errors;
    },
    destinyErrors() {
      const errors = [];
      if (!this.$v.editedItem.destiny.$dirty) return errors;
      !this.$v.editedItem.destiny.required && errors.push("Campo Obrigatório.");
      return errors;
    }
  },

  watch: {
    dialog(val) {
      val || this.close();
    },
    selectedProject() {
      var project = this.projects.data.find(
        item => item.data.attributes.name == this.selectedProject
      );

      this.$store.dispatch("fetchProjectWorkflow", project.data.id);
    }
  },

  methods: {
    deleteItem(item) {
      if (confirm("Deseja realmente remover este passo do fluxo?")) {
        this.$store.dispatch("removeProjectWorkflow", {
          project_id: item.links.project.data.id,
          project_workflow_id: item.data.id
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
      var origin = this.projectStatuses.data.find(
        item => item.data.attributes.name == this.editedItem.origin
      );

      var destiny = this.projectStatuses.data.find(
        item => item.data.attributes.name == this.editedItem.destiny
      );

      var project = this.projects.data.find(
        item => item.data.attributes.name == this.selectedProject
      );

      this.$store.dispatch("createProjectWorkflow", {
        project_id: project.data.id,
        old_status_id: origin.data.id,
        new_status_id: destiny.data.id
      });
      this.close();
    }
  }
};
</script>

<style>
</style>
