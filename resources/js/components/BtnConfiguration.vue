<template>
  <v-speed-dial v-model="fab" absolute bottom right direction="top" transition="slide-y-transition">
    <template v-slot:activator>
      <v-btn v-model="fab" color="blue darken-2" dark fab>
        <v-icon v-if="fab">mdi-close</v-icon>
        <v-icon v-else>mdi-tune</v-icon>
      </v-btn>
    </template>

    <v-tooltip left>
      <template v-slot:activator="{ on }">
        <v-btn to="project-status" fab dark small color="green" v-on="on">
          <v-icon>mdi-bookmark-outline</v-icon>
        </v-btn>
      </template>
      <span>Gerenciar Status</span>
    </v-tooltip>

    <v-tooltip left>
      <template v-slot:activator="{ on }">
        <v-btn to="project-workflow" fab dark small color="indigo" v-on="on">
          <v-icon>mdi-bookmark-multiple-outline</v-icon>
        </v-btn>
      </template>
      <span>Gerenciar Workflow</span>
    </v-tooltip>
    <v-tooltip left>
      <template v-slot:activator="{ on }">
        <v-btn to="field" fab dark small color="pink" v-on="on">
          <v-icon>mdi-focus-field-horizontal</v-icon>
        </v-btn>
      </template>
      <span>Campos de Projeto</span>
    </v-tooltip>
  </v-speed-dial>
</template>

<script>
export default {
  name: "BtnConfiguration",
  data: () => ({
    fab: false
  })
};
</script>

<style >
</style>
