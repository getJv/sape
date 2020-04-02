<template>
  <v-app id="inspire">
    <Sidebar :drawer.sync="drawer" />

    <v-app-bar app clipped-left>
      <v-app-bar-nav-icon @click.stop="drawer = !drawer" />
      <v-toolbar-title>SAPE</v-toolbar-title>
    </v-app-bar>

    <v-content>
      <v-container fluid>
        <v-row align="baseline" justify="start">
          <v-col>
            <router-view></router-view>
          </v-col>
        </v-row>
      </v-container>
      <BtnConfiguration />
    </v-content>

    <v-footer app>
      <span>&copy; 2019</span>
    </v-footer>
  </v-app>
</template>

<script>
import Sidebar from "./Sidebar";
import BtnConfiguration from "../components/BtnConfiguration";

export default {
  name: "App",

  components: {
    Sidebar,
    BtnConfiguration
  },
  props: {
    source: String
  },
  data: () => ({
    bottomNav: "recent",
    drawer: true
  }),
  created() {
    this.$vuetify.theme.dark = true;
  }
};
</script>

<style>
</style>
