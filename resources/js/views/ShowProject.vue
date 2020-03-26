<template>
    <v-row v-if="project">
        <v-col cols="12" sm="6">
            <v-row>
                <v-col cols="12">
                    <v-card>
                        <v-card-title>
                            <p>{{ project.data.attributes.name }}</p>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <p>{{ project.data.attributes.description }}</p>
                        </v-card-text>
                    </v-card>
                </v-col>
                <v-col cols="12">
                    <v-card>
                        <v-card-title>
                            <p>Características do projeto</p>
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <projectDetails :items="desserts" />
                        </v-card-text>
                    </v-card>
                </v-col>
            </v-row>
        </v-col>
        <v-col cols="12" sm="6">
            <v-row>
                <v-col cols="12"> <ProjectMapLayers /> </v-col>
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
export default {
    name: "ShowProject",
    components: {
        ProjectStepper,
        ProjectDetails,
        ProjectMapLayers
    },
    computed: {
        ...mapGetters(["project"])
    },
    data() {
        return {
            desserts: [
                {
                    name: "Valor do Investimento",
                    calories: "R$ 123.123.123,00"
                },
                {
                    name: "Tipo",
                    calories: "Ferroviário"
                },
                {
                    name: "Contato",
                    calories: "Governador: 3333-3333"
                },
                {
                    name: "Territorio",
                    calories: "33000 KM² "
                }
            ]
        };
    }
};
</script>

<style></style>
