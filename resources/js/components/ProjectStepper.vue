<template>
  <v-card>
    <v-card-title>Fases do Projeto</v-card-title>
    <v-divider></v-divider>
    <v-card-text>
      <p
        class="text-center"
        v-if="!projectWorkflow"
      >Este projeto ainda não possui um workflow. Crie um no menu Gerenciar de workflow.</p>
      <v-stepper v-else v-model="e13" vertical>
        <span v-for="(phase,index) in projectWorkflow.data" :key="index+1">
          <v-stepper-step :step="index+1" @click="e13 = index+1" :complete="e13 < 1">
            <span>
              {{phase.links.old_status.data.attributes.name}}
              <BtnAddEvent :hasSession="true" :step_id="index+1" />
            </span>
          </v-stepper-step>

          <v-stepper-content :step="index+1">
            <ul>
              <li
                v-for="event in [1, 2, 3]"
                :key="event"
                class="mb-3"
              >Fase: {{ index+1 }}, Evento: {{ event }}</li>
            </ul>
          </v-stepper-content>
        </span>
        <!-- Ultimo Step do pw -->
        <span>
          <v-stepper-step
            v-if="projectWorkflow"
            :step="projectWorkflow.workflow_steps +1"
            @click="e13 = projectWorkflow.workflow_steps +1"
            :complete="e13 < 2"
          >
            <span>
              {{projectWorkflow.data[projectWorkflow.workflow_steps - 1].links.new_status.data.attributes.name}}
              <BtnAddEvent :hasSession="true" :step_id="projectWorkflow.workflow_steps +1" />
            </span>
          </v-stepper-step>

          <v-stepper-content :step="projectWorkflow.workflow_steps +1">
            <ul>
              <li>teste</li>
            </ul>
          </v-stepper-content>
        </span>
      </v-stepper>
    </v-card-text>
  </v-card>
</template>

<script>
import BtnAddEvent from "../components/BtnAddEvent";
import { mapGetters } from "vuex";

export default {
  name: "ProjecStepper",
  components: {
    BtnAddEvent
  },
  computed: {
    ...mapGetters(["projectWorkflow"])
  },
  methods: {
    teste() {
      console.log(this.projectWorkflow.data);
    }
  },
  data() {
    return {
      e13: 1
    };
  }
};
</script>

<style></style>
