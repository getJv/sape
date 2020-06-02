<template>
  <v-tooltip bottom>
    <template v-slot:activator="{ on }">
      <v-icon
        small
        :color="color"
        class="pa-1"
        v-on="on"
        dark
        @click="$emit('update:activeOrder',!activeOrder);"
      >mdi-cursor-move</v-icon>
    </template>
    <span>{{ exibirTooltip }}</span>
  </v-tooltip>
</template>

<script>
export default {
  name: "BtnProjectOrder",
  props: ["activeOrder"],
  computed: {
    exibirTooltip() {
      if (!this.activeOrder) {
        return "Ativar ordenador";
      } else {
        return "Desativar Ordenador";
      }
    },
    color() {
      if (!this.activeOrder) {
        return "blue lighten-3";
      } else {
        return "blue darken-3";
      }
    }
  }
};
</script>

<style></style>
