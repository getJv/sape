<template>
    <v-card>
        <v-card-title>Fases do Projeto</v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <p class="text-center" v-if="!projectWorkflow">
                Este projeto ainda não possui um workflow. Crie um no menu
                Gerenciar de workflow.
            </p>
            <v-stepper v-else v-model="e13" vertical>
                <span
                    v-for="(phase, index) in projectWorkflow.data"
                    :key="index + 1"
                >
                    <v-stepper-step
                        :step="index + 1"
                        @click="e13 = index + 1"
                        :complete="e13 < 1"
                    >
                        <span>
                            {{ phase.links.old_status.data.attributes.name }}
                            <BtnAddEvent
                                :owner_id="phase.links.old_status.data.id"
                                :project_workflow_id="phase.data.id"
                                :step_id="index + 1"
                            />
                        </span>
                    </v-stepper-step>

                    <v-stepper-content :step="index + 1">
                        <span
                            v-for="event in phase.links.events.data"
                            :key="event.data.id"
                            class="mb-3"
                        >
                            <v-row
                                justify="start"
                                align="center"
                                v-if="
                                    event.data.attributes.owner_id ==
                                        phase.links.old_status.data.id
                                "
                            >
                                <v-col cols="12">
                                    <v-tooltip left>
                                        <template v-slot:activator="{ on }">
                                            <span v-on="on">
                                                {{
                                                    event.data.attributes
                                                        .created_at | moment
                                                }}
                                                -
                                                {{ event.data.attributes.name }}
                                            </span>
                                        </template>
                                        <span>
                                            <v-card>
                                                <v-card-title colo="cyan">
                                                    {{
                                                        event.data.attributes
                                                            .name
                                                    }}
                                                </v-card-title>
                                                <v-divider></v-divider>
                                                <v-card-text>
                                                    <v-row>
                                                        <v-col cols="12"
                                                            >Criado em:
                                                            {{
                                                                event.data
                                                                    .attributes
                                                                    .created_at
                                                                    | moment
                                                            }}
                                                        </v-col>
                                                        <v-col cols="12"
                                                            >Responsável:</v-col
                                                        >
                                                        <v-col cols="12">
                                                            <p class="title">
                                                                Descrição
                                                            </p>
                                                            {{
                                                                event.data
                                                                    .attributes
                                                                    .description
                                                            }}</v-col
                                                        >
                                                    </v-row>
                                                </v-card-text>
                                            </v-card>
                                        </span>
                                    </v-tooltip>
                                </v-col>
                            </v-row>
                        </span>
                    </v-stepper-content>
                </span>
                <!-- Ultimo Step do pw -->
                <span>
                    <v-stepper-step
                        v-if="projectWorkflow"
                        :step="projectWorkflow.workflow_steps + 1"
                        @click="e13 = projectWorkflow.workflow_steps + 1"
                        :complete="e13 < 2"
                    >
                        <span>
                            {{
                                last_step.links.new_status.data.attributes.name
                            }}
                            <BtnAddEvent
                                :owner_id="last_step.links.new_status.data.id"
                                :project_workflow_id="last_step.data.id"
                                :step_id="projectWorkflow.workflow_steps + 1"
                            />
                        </span>
                    </v-stepper-step>

                    <v-stepper-content
                        :step="projectWorkflow.workflow_steps + 1"
                    >
                        <span
                            v-for="event in last_step.links.events.data"
                            :key="event.data.id"
                            class="mb-3"
                        >
                            <v-row
                                justify="start"
                                align="center"
                                v-if="
                                    event.data.attributes.owner_id ==
                                        last_step.links.old_status.data.id
                                "
                            >
                                <v-col cols="12">
                                    <v-tooltip left>
                                        <template v-slot:activator="{ on }">
                                            <span v-on="on">
                                                {{
                                                    event.data.attributes
                                                        .created_at | moment
                                                }}
                                                -
                                                {{ event.data.attributes.name }}
                                            </span>
                                        </template>
                                        <span>
                                            <v-card>
                                                <v-card-title colo="cyan">
                                                    {{
                                                        event.data.attributes
                                                            .name
                                                    }}
                                                </v-card-title>
                                                <v-divider></v-divider>
                                                <v-card-text>
                                                    <v-row>
                                                        <v-col cols="12"
                                                            >Criado em:
                                                            {{
                                                                event.data
                                                                    .attributes
                                                                    .created_at
                                                                    | moment
                                                            }}
                                                        </v-col>
                                                        <v-col cols="12"
                                                            >Responsável:</v-col
                                                        >
                                                        <v-col cols="12">
                                                            <p class="title">
                                                                Descrição
                                                            </p>
                                                            {{
                                                                event.data
                                                                    .attributes
                                                                    .description
                                                            }}</v-col
                                                        >
                                                    </v-row>
                                                </v-card-text>
                                            </v-card>
                                        </span>
                                    </v-tooltip>
                                </v-col>
                            </v-row>
                        </span>
                    </v-stepper-content>
                </span>
            </v-stepper>
        </v-card-text>
    </v-card>
</template>

<script>
import BtnAddEvent from "../components/BtnAddEvent";
import { mapGetters } from "vuex";
import moment from "moment";
/**TODO: transformar o tooltip em componente dinamico */
export default {
    name: "ProjecStepper",
    components: {
        BtnAddEvent
    },
    filters: {
        moment: function(date) {
            return moment(date).format("D / M / Y");
        }
    },
    computed: {
        ...mapGetters(["projectWorkflow"]),
        last_step() {
            return this.projectWorkflow.data[
                this.projectWorkflow.workflow_steps - 1
            ];
        }
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
