<template>
  <div v-if="projectWorkflow && stepList.length > 0">
    <v-data-table
      :headers="headers"
      :items="attachments.data"
      sort-by="step"
      class="elevation-1"
      hide-default-footer
    >
      <template v-slot:top>
        <v-toolbar flat color="white">
          <v-toolbar-title>Anexos</v-toolbar-title>
          <v-divider class="mx-4" inset vertical></v-divider>
          <div class="flex-grow-1"></div>
          <v-dialog v-model="dialog" max-width="500px">
            <template v-slot:activator="{ on }">
              <v-btn
                color="primary"
                dark
                class="mb-2"
                v-on="on"
                v-if="isAuthenticated"
                @click="reset"
              >Adicionar Novo</v-btn>
            </template>
            <v-card>
              <v-card-title>
                <span class="headline">Cadastro de Anexo</span>
              </v-card-title>
              <v-card-text>
                <v-container>
                  <v-row>
                    <v-col cols="12">
                      <v-file-input
                        v-if="dialog"
                        :rules="fileRules"
                        accept="image/png, image/jpeg, image/bmp, image/gif, application/pdf"
                        placeholder="Clique para selecionar um arquivo"
                        prepend-icon="mdi-file"
                        label="Arquivo"
                        v-model="editedItem.arquivo"
                        required
                        :error-messages="arquivoErrors"
                        @input="
                                                    $v.editedItem.arquivo.$touch()
                                                "
                        @blur="
                                                    $v.editedItem.arquivo.$touch()
                                                "
                      ></v-file-input>
                    </v-col>
                    <v-col cols="12">
                      <v-select
                        v-if="dialog"
                        :items="stepList"
                        label="Fase de vinculação do arquivo"
                        solo
                        v-model="
                                                    editedItem.workflow_step
                                                "
                        required
                      ></v-select>
                    </v-col>
                  </v-row>
                </v-container>
              </v-card-text>

              <v-card-actions>
                <div class="flex-grow-1"></div>
                <v-btn color="blue darken-1" text @click="close">Cancelar</v-btn>
                <v-btn color="blue darken-1" text @click="save" :loading="editedItem.loading">Salvar</v-btn>
              </v-card-actions>
            </v-card>
          </v-dialog>
        </v-toolbar>
      </template>
      <template v-slot:item.workflow_step="{ item }">
        {{
        item.links.status.data.attributes.name
        }}
      </template>
      <template v-slot:item.file_name="{ item }">
        <span style="cursor:pointer" @click="download(item)">
          {{
          item.data.attributes.original_filename
          }}
        </span>
      </template>

      <template v-slot:item.action="{ item }">
        <v-icon color="red" small @click="deleteItem(item)">mdi-delete</v-icon>
      </template>
      <template v-slot:item.arquivo_nome="{ item }">
        <u>
          <span style="cursor:pointer" @click="download(item)">
            {{
            item.arquivo_nome
            }}
          </span>
        </u>
      </template>
      <template v-slot:no-data>Nada cadastrado ainda...</template>
    </v-data-table>
  </div>
</template>
<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
import { mapGetters } from "vuex";

export default {
  mixins: [validationMixin],
  validations: {
    editedItem: {
      arquivo: { required },
      tipoArquivo: { required }
    }
  },

  data: () => ({
    dialog: false,
    headers: [
      { text: "Fase", value: "workflow_step", sortable: true },
      { text: "Arquivo", value: "file_name", sortable: true },
      { text: "Ações", value: "action", sortable: false }
    ],
    items: [],
    editedItem: {
      workflow_step_id: "",
      loading: false
    },
    defaultItem: {
      workflow_step: ""
    },
    fileRules: [
      value =>
        !value ||
        value.size < 5000000 ||
        "Cada arquivo deve ter no máximo 5 MB!"
    ]
  }),
  watch: {
    dialog(val) {
      val || this.close();
    }
  },
  computed: {
    ...mapGetters([
      "isAuthenticated",
      "projectWorkflow",
      "project",
      "projectStatuses",
      "attachments"
    ]),

    stepList() {
      var list = [];
      var steps_number = this.projectWorkflow.workflow_steps;
      this.projectWorkflow.data.forEach(item => {
        list.push(item.links.old_status.data.attributes.name);
      });
      if (steps_number > 1) {
        list.push(
          this.projectWorkflow.data[steps_number - 1].links.new_status.data
            .attributes.name
        );
      }
      return list;
    },
    arquivoErrors() {
      const errors = [];
      if (!this.$v.editedItem.arquivo.$dirty) return errors;
      !this.$v.editedItem.arquivo.required && errors.push("Campo Obrigatório.");
      return errors;
    }
  },
  methods: {
    download(item) {
      axios({
        url: "api/attachments/download/" + item.data.id,

        method: "GET",

        responseType: "blob"
      }).then(response => {
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));

        var fileLink = document.createElement("a");

        fileLink.href = fileURL;

        fileLink.setAttribute(
          "download",
          item.data.attributes.original_filename
        );

        document.body.appendChild(fileLink);

        fileLink.click();
      });
    },
    reset() {
      this.$v.$reset;
    },
    deleteItem(item) {
      const index = this.items.indexOf(item);
      if (!confirm("Deseja realmente excluir este item?")) return;
      this.$store.dispatch("deleteAttach", item.data.id);
    },

    close() {
      this.dialog = false;
      setTimeout(() => {
        this.editedItem = Object.assign({}, this.defaultItem);
      }, 300);
    },

    /*  podeDeletar(item) {
            return (
                ("solicitacao" == item.origem && this.viagem_status_id == 1) ||
                ("prestacao" == item.origem &&
                    (this.viagem_status_id == 2 ||
                        this.viagem_status_id == 3 ||
                        this.viagem_status_id == 7))
            );
        }, */

    /*  podeAdicionar() {
            return (
                ("solicitacao" == this.origem && this.viagem_status_id == 1) ||
                ("prestacao" == this.origem &&
                    (this.viagem_status_id == 2 ||
                        this.viagem_status_id == 3 ||
                        this.viagem_status_id == 7))
            );
        },
 */
    save() {
      const fd = new FormData();
      fd.append("project_id", this.project.data.id);
      fd.append("file", this.editedItem.arquivo);

      var status = this.projectStatuses.data.find(item => {
        if (item.data.attributes.name == this.editedItem.workflow_step) {
          return item;
        }
      });

      fd.append("status_id", status.data.id);

      this.$store.dispatch("createAttachment", fd);
      this.close();
    }
  }
};
</script>
