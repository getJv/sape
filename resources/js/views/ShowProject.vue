<template>
  <v-row v-if="project">
    <v-col cols="12" sm="6">
      <v-row>
        <v-col cols="12">
          <v-card>
            <v-card-title>
              {{ project.data.attributes.name }}
              <BtnEditProject :hasSession="hasSession" :item="project" />
            </v-card-title>
            <v-divider></v-divider>
            <v-card-text>
              <p>{{ project.data.attributes.description }}</p>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col cols="12">
          <projectDetails :hasSession="hasSession" />
        </v-col>
      </v-row>
    </v-col>
    <v-col cols="12" sm="6">
      <v-row>
        <!--  <v-col cols="12">
          <ProjectMapLayers />
        </v-col>-->
        <v-col cols="12">
          <ProjectStepper />
        </v-col>
      </v-row>
    </v-col>
  </v-row>
</template>

<script>
import { mapGetters } from "vuex";
import ProjectStepper from "../components/ProjectStepper";
import ProjectDetails from "../components/ProjectDetails";
import ProjectMapLayers from "../components/ProjectMapLayers";
import BtnEditProject from "../components/BtnEditProject";

export default {
  name: "ShowProject",
  components: {
    ProjectStepper,
    ProjectDetails,
    ProjectMapLayers,
    BtnEditProject
  },
  computed: {
    ...mapGetters(["project"])
  },
  data() {
    return {
      hasSession: true
    };
  }
};
</script>

<style></style>
