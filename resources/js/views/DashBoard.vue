<template>
  <v-container v-if="$store.getters.projects && $store.getters.dashboards " fluid>
    <v-row align="start" justify="start">
      <v-col cols="12" md="4" v-for="item in $store.getters.dashboards.data" :key="item.data.id">
        <v-card>
          <v-card-title>
            <span>
              {{ field_name(item.data.attributes.field_id) }}
              <v-icon color="red" class="ml-4" small @click="deleteItem(item)">mdi-delete</v-icon>
            </span>
          </v-card-title>
          <v-card-text>
            <v-data-table
              v-if="item.data.attributes.operation == 'group'"
              :headers="headersPreview"
              :items="item.data.attributes.data"
              :items-per-page="5"
            ></v-data-table>

            <v-card
              v-else-if="item.data.attributes.operation == 'sum'"
              class="mx-auto"
              max-width="344"
              outlined
            >
              <v-card-text>
                <div class="subtitle mb-4">{{item.data.attributes.data[0].item }}</div>
                <div
                  class="display-2 text-center"
                >{{ Math.round(item.data.attributes.data[0].value * 100)/100 | currency }}</div>
              </v-card-text>
            </v-card>

            <v-card
              v-else-if="item.data.attributes.operation == 'avg'"
              class="mx-auto"
              max-width="344"
              outlined
            >
              <v-card-text>
                <div class="subtitle mb-4">{{item.data.attributes.data[0].item }}</div>
                <div
                  class="display-2 text-center"
                >{{ Math.round(item.data.attributes.data[0].value * 100)/100 | currency}}</div>
              </v-card-text>
            </v-card>

            <v-data-table
              v-else-if="item.data.attributes.operation == 'table'"
              :headers="headersPreview"
              :items="item.data.attributes.data"
              :items-per-page="3"
            ></v-data-table>
          </v-card-text>
        </v-card>
      </v-col>
    </v-row>
  </v-container>
</template>

<script>
export default {
  name: "Dashboard",
  components: {},
  created() {
    this.$store.dispatch("fetchDashboards");
    this.$store.dispatch("fetchFields");
  },
  methods: {
    field_name(field_id) {
      return this.$store.getters.fields.data.find(
        item => item.data.id == field_id
      ).data.attributes.name;
    },
    deleteItem(item) {
      this.$store.dispatch("deleteDashboard", item.data.id);
    }
  },
  data() {
    return {
      headersPreview: [
        { text: "Item", value: "item" },
        { text: "Valor", value: "value" }
      ]
    };
  }
};
</script>

<style></style>
