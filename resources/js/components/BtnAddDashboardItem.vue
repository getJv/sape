<template>
  <v-dialog v-model="dialog" fullscreen hide-overlay transition="dialog-bottom-transition">
    <template v-slot:activator="{ on }">
      <v-icon small color="primary" hint="Adicionar atividade" v-on="on" dark>mdi-toy-brick-plus</v-icon>
    </template>
    <v-card :loading="allProjectFields == null">
      <v-toolbar dark color="primary">
        <v-btn icon dark @click="dialog = false">
          <v-icon>mdi-close</v-icon>
        </v-btn>
        <v-toolbar-title>Novo item para DashBoard</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
          <v-btn dark text @click="salvar">Salvar</v-btn>
        </v-toolbar-items>
      </v-toolbar>
      <v-card-text v-if="allProjectFields">
        <v-row align="start" justify="center">
          <!-- Coluna 1-->
          <v-col cols="12" md="5">
            <div class="title font-weight-bold mb-3">1º Entrada de dados</div>
            <FilterField
              label="Selecione o campo desejado"
              :items="fieldItems"
              :defaultOption="selectedField"
              :value.sync="selectedField"
            />
            <div v-if="selectedField">
              <div class="title font-weight-bold mb-3">Visualização dos dados disponíveis</div>
              <v-data-table
                :headers="headers"
                :items="selectedItemValues"
                :items-per-page="5"
                class="elevation-1"
              ></v-data-table>
            </div>
          </v-col>
          <!-- Coluna 2-->
          <v-col cols="12" md="5" v-if="selectedField">
            <div class="title font-weight-bold mb-3">2º Prévisualização do item</div>
            <OperationField
              label="Selecione o tipo de operação"
              :items="operationOptions"
              :defaultOption="selectedOperation"
              :value.sync="selectedOperation"
            />
            {{selectedOperation}}
            <v-card v-if="selectedOperation">
              <v-card-title>{{ selectedField }}</v-card-title>
              <v-card-text>
                <v-row>
                  <v-col v-if="selectedOperation == 'Agrupamento'" cols="12">
                    <v-data-table
                      :headers="headersPreview"
                      :items="groupFunction"
                      :items-per-page="5"
                    ></v-data-table>
                  </v-col>
                  <v-col v-else-if="selectedOperation == 'Somatório'" cols="12">
                    <v-card class="mx-auto" max-width="344" outlined>
                      <v-card-text>
                        <div class="subtitle mb-4">{{selectedOperation }}</div>
                        <div class="display-2 text-center">{{ sumFunction | currency }}</div>
                      </v-card-text>
                    </v-card>
                  </v-col>
                  <v-col v-else-if="selectedOperation == 'Média aritimética'" cols="12">
                    <v-card class="mx-auto" max-width="344" outlined>
                      <v-card-text>
                        <div class="subtitle mb-4">{{selectedOperation }}</div>
                        <div class="display-2 text-center">{{ avgFunction | currency }}</div>
                      </v-card-text>
                    </v-card>
                  </v-col>
                  <v-col v-else cols="12">
                    <v-data-table
                      :headers="headersPreview"
                      :items="tableFunction"
                      :items-per-page="5"
                    ></v-data-table>
                  </v-col>
                </v-row>
              </v-card-text>
            </v-card>
          </v-col>
        </v-row>
      </v-card-text>
    </v-card>
  </v-dialog>
</template>


<script>
import { mapGetters } from "vuex";
import FilterField from "./formInputs/SelectField";
import OperationField from "./formInputs/SelectField";
export default {
  name: "DashBoardItem",
  components: { FilterField, OperationField },

  watch: {
    dialog(value) {
      if (value) {
        this.$store.dispatch("fetchAllProjectFields");
      } else {
        this.reset();
      }
    }
  },
  data() {
    return {
      dialog: false,
      selectedField: "",
      selectedFieldType: "",
      selectedOperation: "",
      selectedView: "",
      headers: [
        { text: "Projeto", value: "project" },
        { text: "Campo", value: "field" },
        { text: "Valor", value: "value" }
      ],
      headersPreview: [
        { text: "Item", value: "item" },
        { text: "Valor", value: "value" }
      ],
      operations: [
        {
          type: "sum",
          name: "Somatório"
        },
        {
          type: "avg",
          name: "Média aritimética"
        },
        {
          type: "table",
          name: "Tabulação Simples"
        },
        {
          type: "group",
          name: "Agrupamento"
        }
      ]
    };
  },
  methods: {
    reset() {
      this.selectedField = null;
      this.selectedOperation = null;
      this.selectedView = null;
      this.dialog = false;
    },
    salvar() {
      var field = this.allProjectFields.data.find(
        item => item.links.field.data.attributes.name == this.selectedField
      );

      this.$store.dispatch("createDashboard", {
        field_id: field.data.attributes.field_id,
        operation: this.operations.find(
          item => item.name == this.selectedOperation
        ).type
      });

      this.dialog = false;
    }
  },
  computed: {
    ...mapGetters(["allProjectFields"]),
    fieldItems() {
      return this.allProjectFields.data.map(
        item => item.links.field.data.attributes.name
      );
    },
    selectedItemValues() {
      var list = this.allProjectFields.data.filter(
        item => item.links.field.data.attributes.name == this.selectedField
      );

      return list.map(item => {
        return {
          project: item.links.project.data.attributes.name,
          field: item.links.field.data.attributes.name,
          value: item.data.attributes.value
        };
      });
    },

    operationOptions() {
      var options = [];

      var pf = this.allProjectFields.data.find(
        item => item.links.field.data.attributes.name == this.selectedField
      );

      this.selectedFieldType = pf.links.field.data.attributes.type;

      if (
        this.selectedFieldType == "integerField" ||
        this.selectedFieldType == "numberField"
      ) {
        options = ["Somatório", "Média aritimética"];
      } else {
        options = ["Agrupamento", "Tabulação Simples"];
      }

      return options;
    },
    groupFunction() {
      var arr = this.selectedItemValues.reduce((total, item) => {
        total[item.value] = (total[item.value] || 0) + 1;
        return total;
      }, {});

      var result = [];
      for (var key in arr) {
        result.push({
          item: key,
          value: arr[key]
        });
      }
      return result;
    },
    tableFunction() {
      return this.selectedItemValues.map(item => {
        return {
          item: item.project,
          value: item.value
        };
      });
    },
    sumFunction() {
      var result = this.selectedItemValues.reduce((total, item) => {
        var num = 0;
        if (this.selectedFieldType == "numberField") {
          num = parseFloat(item.value.replace(",", "."));
        } else {
          num = parseInt(item.value.replace(",", "."));
        }

        total += num || 0;

        return total;
      }, 0);

      return Math.round(result * 100) / 100;
    },
    avgFunction() {
      var result = this.selectedItemValues.reduce((total, item) => {
        var num = 0;
        if (this.selectedFieldType == "numberField") {
          num = parseFloat(item.value.replace(",", "."));
        } else {
          num = parseInt(item.value.replace(",", "."));
        }

        total += num || 0;

        return total;
      }, 0);

      return Math.round((result / this.selectedItemValues.length) * 100) / 100;
    }
  }
};
</script>

<style>
</style>
