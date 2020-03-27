<template>
    <v-card>
        <v-card-title> Fases do Projeto </v-card-title>
        <v-divider></v-divider>
        <v-card-text>
            <v-stepper v-model="e13" vertical>
                <span v-for="phase in [1, 2, 3]" :key="phase">
                    <v-stepper-step
                        :step="phase"
                        @click="e13 = phase"
                        :complete="e13 < 1"
                    >
                        <span>
                            Prospecções de Serviços
                            <BtnAddEvent :hasSession="true"
                        /></span>
                    </v-stepper-step>

                    <v-stepper-content :step="phase">
                        <ul>
                            <li
                                v-for="event in [1, 2, 3]"
                                :key="event"
                                class="mb-3"
                            >
                                Fase: {{ phase }}, Evento: {{ event }}
                            </li>
                        </ul>
                    </v-stepper-content>
                    <div></div>
                </span>
            </v-stepper>
        </v-card-text>
    </v-card>
</template>

<script>
import BtnAddEvent from "../components/BtnAddEvent";
export default {
    name: "ProjecStepper",
    components: {
        BtnAddEvent
    },
    data() {
        return {
            e13: 1
        };
    }
};
</script>

<style></style>
