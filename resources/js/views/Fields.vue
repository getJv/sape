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
                <v-btn absolute right color="primary" outlined small dark v-on="on">Criar novo campo</v-btn>
              </template>
              <v-card>
                <v-card-title>
                  <span class="headline">{{ formTitle }}</span>
                </v-card-title>

                <v-card-text>
                  <v-container>
                    <v-row justify="center" align="center">
                      <v-col cols="12" sm="10">
                        <typeField
                          ref="typeField"
                          label="Formato do dado"
                          :items="fieldTypesList"
                          placeholder="Selecione um dos formatos"
                          :value.sync="editedItem.type"
                          required
                          :ifConditional="editedIndex < 0 && dialog"
                        />
                      </v-col>
                    </v-row>

                    <v-row justify="center" align="center" v-if="editedItem.type">
                      <v-col cols="12" sm="10">
                        <NameField
                          :value.sync="editedItem.name"
                          :min="3"
                          :max="50"
                          label="Nome"
                          required
                        />
                      </v-col>
                      <v-col cols="12" sm="10">
                        <DescriptionField
                          :value.sync="editedItem.description"
                          hint="Informe uma breve descrição deste projeto"
                          label="Descrição do projeto"
                          :min="3"
                          :max="255"
                        />
                      </v-col>

                      <v-col cols="12" sm="10" v-if="showField('mask')">
                        <v-select
                          v-model="editedItem.mask"
                          :items="fieldMaskList"
                          label="Máscara para numero numero"
                          placeholder="Selecione um formato de máscara"
                          outlined
                          required
                        ></v-select>
                      </v-col>
                      <v-col
                        cols="12"
                        sm="10"
                        v-if="showField('min-max') && editedItem.mask == 'Sem máscara' && editedItem.mask == 'Sem máscara'"
                      >
                        <v-row>
                          <v-col cols="6">
                            <minField
                              label="Quantidade mínima de caracteres"
                              :value.sync="editedItem.min"
                            />
                          </v-col>
                          <v-col cols="6">
                            <maxField
                              label="Quantidade máxima de caracteres"
                              :value.sync="editedItem.max"
                            />
                          </v-col>
                        </v-row>
                      </v-col>
                      <v-col cols="12" sm="10" v-if="showField('enum')">
                        <EnumListField
                          :value.sync="editedItem.items"
                          label="Liste as opções do campo"
                          hint="Cada opção deve ser separada por um ponto-e-virgula"
                          required
                        />
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
        <template v-slot:item.name="{ item }">
          {{
          item.data.attributes.name
          }}
        </template>
        <template v-slot:item.description="{ item }">
          {{
          item.data.attributes.description
          }}
        </template>
        <template v-slot:item.type="{ item }">
          {{
          item.data.attributes.type
          }}
        </template>
        <template v-slot:item.min="{ item }">
          <span v-if="item.data.attributes.type == 'dateField'">-- Não utiliza --</span>
          <span v-else-if="!item.data.attributes.min">-- Não informado --</span>
          <span v-else>{{item.data.attributes.min}}</span>
        </template>
        <template v-slot:item.max="{ item }">
          <span v-if="item.data.attributes.type == 'dateField'">-- Não utiliza --</span>
          <span v-else-if="!item.data.attributes.max">-- Não informado --</span>
          <span v-else>{{item.data.attributes.max}}</span>
        </template>
        <template v-slot:item.mask="{ item }">
          <span v-if="item.data.attributes.type != 'integerField'">-- Não utiliza --</span>
          <span v-else-if="!item.data.attributes.mask">-- Não informado --</span>
          <span v-else>{{item.data.attributes.mask}}</span>
        </template>
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
import { mapGetters } from "vuex";
import minField from "../components/formInputs/IntegerField";
import maxField from "../components/formInputs/IntegerField";
import typeField from "../components/formInputs/SelectField";
import NameField from "../components/formInputs/TextField";
import DescriptionField from "../components/formInputs/TextAreaField";
import EnumListField from "../components/formInputs/EnumListField";

export default {
  name: "Fields",
  components: {
    minField,
    maxField,
    typeField,
    NameField,
    DescriptionField,
    EnumListField
  },
  created() {
    this.$store.dispatch("fetchFields");
  },
  data: () => ({
    dialog: false,
    editedIndex: -1,
    editedItem: {
      id: "",
      name: "",
      type: "",
      mask: "Sem máscara",
      items: "",
      description: "",
      active: true
    },
    defaultItem: {
      name: "",
      type: "",
      mask: "Sem máscara",
      items: "",
      description: "",
      active: true
    }
  }),

  computed: {
    ...mapGetters(["fields", "maskTypes", "fieldTypes"]),
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
      return this.fieldTypes.find(item => item.name == this.editedItem.type)
        .type;
    },
    fieldMaskSelected() {
      if (!this.editedItem.mask) return null;

      return this.maskTypes.find(item => item.name == this.editedItem.mask)
        .format;
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
          text: "Tipo de campo",
          value: "type"
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
      return headers;
    },

    formTitle() {
      return this.editedIndex === -1 ? "Novo Item" : "Edição de Item";
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
    /**
     * Metodo identifica quais subcampos campos recarregar conforme seleção do tipo do campo
     */
    showField(fieldname) {
      var attachedFields = this.fieldTypes.find(item => {
        return (
          item.name == this.editedItem.type || item.type == this.editedItem.type // item.name to create form, item.type to edit form
        );
      }).fields;

      return attachedFields.find(item => item == fieldname);
    },

    close() {
      this.dialog = false;
      this.defaultItem.type = null;

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
          max: this.editedItem.max ?? 0,
          min: this.editedItem.min ?? 0,
          mask: this.fieldMaskSelected,
          items: this.editedItem.items,
          active: this.editedItem.active
        });
      } else {
        this.$store.dispatch("createField", {
          name: this.editedItem.name,
          description: this.editedItem.description,
          max: this.editedItem.max ?? 0,
          min: this.editedItem.min ?? 0,
          mask: this.fieldMaskSelected,
          items: this.editedItem.items,
          type: this.fieldTypeSelected
        });
      }
      this.close();
    }
  }
};
</script>

<style></style>
