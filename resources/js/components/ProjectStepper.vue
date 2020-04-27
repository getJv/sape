<template>
  <v-card outlined>
    <v-card-title>Fases do Projeto</v-card-title>
    <v-divider></v-divider>
    <v-card-text>
      <p class="text-center" v-if="!projectWorkflow || projectWorkflow.data.length < 1">
        Este projeto ainda não possui um workflow. Crie um no menu
        Gerenciar de workflow.
      </p>
      <v-stepper flat v-else v-model="e13" vertical>
        <span v-for="(phase, index) in projectWorkflow.data" :key="phase.data.attributes.order">
          <v-stepper-step
            :step="phase.data.attributes.order"
            @click="e13 = phase.data.attributes.order"
          >
            <span>
              {{ phase.links.old_status.data.attributes.name }}
              <BtnAddEvent
                :owner_id="phase.links.old_status.data.id"
                :project_workflow_id="phase.data.id"
                :step_id="phase.data.attributes.order"
              />
            </span>
          </v-stepper-step>
          <v-stepper-content class="mb-3" :step="phase.data.attributes.order">
            <v-card color="grey lighten-1">
              <v-card-text>
                <div v-if="index == 0">
                  <StepCard
                    v-for="event in eventFinder(
                                            phase.links.events,
                                            phase.data.attributes.old_status_id
                                        )"
                    :key="event.data.id"
                    :item="event"
                  />
                </div>
                <div v-else>
                  <StepCard
                    v-for="event in [
                                            ...eventFinder(
                                                projectWorkflow.data[index - 1]
                                                    .links.events,
                                                phase.data.attributes
                                                    .old_status_id
                                            ),
                                            ...eventFinder(
                                                phase.links.events,
                                                phase.data.attributes
                                                    .old_status_id
                                            )
                                        ]"
                    :key="event.data.id"
                    :item="event"
                  />
                </div>
              </v-card-text>
            </v-card>
          </v-stepper-content>
          <v-divider></v-divider>
        </span>

        <!-- Ultimo Step do pw -->

        <v-stepper-step
          v-if="projectWorkflow"
          :step="projectWorkflow.workflow_steps + 1"
          @click="e13 = projectWorkflow.workflow_steps + 1"
        >
          <span>
            {{ last_step.links.new_status.data.attributes.name }}
            <BtnAddEvent
              :owner_id="last_step.links.new_status.data.id"
              :project_workflow_id="last_step.data.id"
              :step_id="projectWorkflow.workflow_steps + 1"
            />
          </span>
        </v-stepper-step>
        <v-stepper-content :step="projectWorkflow.workflow_steps + 1">
          <v-card color="grey lighten-1">
            <v-card-text>
              <StepCard
                v-for="event in eventFinder(
                                            last_step.links.events,
                                            last_step.data.attributes.new_status_id
                                        )"
                :key="event.data.id"
                :item="event"
              />
            </v-card-text>
          </v-card>
        </v-stepper-content>
      </v-stepper>
    </v-card-text>
  </v-card>
</template>

<script>
import StepCard from "../components/StepCard";
import BtnAddEvent from "../components/BtnAddEvent";
import { mapGetters } from "vuex";

/**TODO: transformar o tooltip em componente dinamico */
export default {
  name: "ProjecStepper",
  components: {
    StepCard,
    BtnAddEvent
  },

  computed: {
    ...mapGetters(["projectWorkflow"]),

    last_step() {
      return this.projectWorkflow.data[this.projectWorkflow.workflow_steps - 1];
    }
  },
  methods: {
    eventFinder(eventCollection, stepId) {
      //return phase.links.events.data.filter(
      return eventCollection.data.filter(
        event =>
          event.data.attributes.owner_id ==
          //phase.data.attributes.old_status_id
          stepId
      );
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
