<template>
  <v-card outlined color="transparent">
    <v-card-text>
      <div class="subtitle font-weight-black">Atividades da fase</div>
      <v-row justify="start" align="center">
        <v-col cols="12">
          <v-tooltip color="transparent" left>
            <template v-slot:activator="{ on }">
              <v-list-item v-on="on">
                <v-list-item-content>
                  <v-list-item-title>
                    {{ item.data.attributes.created_at | moment }}
                    -
                    {{ item.data.attributes.name }}
                    <v-icon color="red red-lighten-3" small @click="deleteItem(item)">mdi-delete</v-icon>
                  </v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </template>
            <span>
              <v-card dark>
                <v-card-title>{{ item.data.attributes.name }}</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                  <v-row>
                    <v-col cols="12">
                      Criado em:
                      {{
                      item.data.attributes.created_at | moment
                      }}
                    </v-col>
                    <v-col cols="12">Responsável:</v-col>
                    <v-col cols="12">
                      <p class="title">Descrição</p>
                      {{
                      item.data.attributes.description
                      }}
                    </v-col>
                  </v-row>
                </v-card-text>
              </v-card>
            </span>
          </v-tooltip>
        </v-col>
      </v-row>
    </v-card-text>
  </v-card>
</template>
<script>
import moment from "moment";

export default {
  name: "StepCard",
  props: ["item"],
  methods: {
    deleteItem(item) {
      if (!confirm("Deseja realmente excluir este item?")) return;
      this.$store.dispatch("deleteEvent", item.data.id);
    }
  },

  filters: {
    moment: function(date) {
      return moment(date).format("D/M/Y");
    }
  }
};
</script>
