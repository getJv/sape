<template>
  <v-tooltip bottom>
    <template v-slot:activator="{ on }">
      <v-icon
        small
        :color="color"
        class="pa-1"
        v-on="on"
        dark
        @click="$emit('update:showAll',!showAll)"
      >{{ exibirIcone }}</v-icon>
    </template>
    <span>{{ exibirTooltip }}</span>
  </v-tooltip>
</template>

<script>
export default {
  name: "BtnProjectFilter",
  props: ["showAll"],
  computed: {
    exibirIcone() {
      if (this.showAll) {
        return "mdi-layers-outline";
      } else {
        return "mdi-layers-off-outline";
      }
    },
    exibirTooltip() {
      if (!this.showAll) {
        return "Exibir todos";
      } else {
        return "Exibir apenas ativos";
      }
    },
    color() {
      if (!this.showAll) {
        return "green lighten-3";
      } else {
        return "green darken-3";
      }
    }
  }
};
</script>

<style></style>
