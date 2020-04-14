<template>
    <v-navigation-drawer
        color="blue-grey lighten-5"
        ref="navbar"
        @input="ocultar"
        :value="drawer"
        app
        clipped
    >
        <v-list dense>
            <v-list-item @click="$router.push('/')" link>
                <v-list-item-action>
                    <v-icon color="green darken-1">mdi-view-dashboard</v-icon>
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>Dashboard</v-list-item-title>
                </v-list-item-content>
            </v-list-item>
            <v-list-item link>
                <v-list-item-action>
                    <v-icon color="pink darken-1"
                        >mdi-toy-brick-marker-outline</v-icon
                    >
                </v-list-item-action>
                <v-list-item-content>
                    <v-list-item-title>
                        <span>
                            Projetos
                            <BtnAddProject :hasSession="true" />
                        </span>
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <span v-if="projects">
                <v-list-item
                    v-for="item in projects.data"
                    :key="item.data.id"
                    link
                >
                    <v-list-item-content @click="showProject(item)">
                        <v-list-item-title>
                            {{ item.data.attributes.name }}
                        </v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </span>
        </v-list>
    </v-navigation-drawer>
</template>

<script>
import BtnAddProject from "./BtnAddProject";
import { mapGetters } from "vuex";

export default {
    name: "Sidebar",
    props: ["drawer"],
    components: {
        BtnAddProject
    },
    methods: {
        ocultar() {
            if (!this.$refs.navbar.isActive) {
                this.$emit("update:drawer", false);
            }
        },
        showProject(item) {
            this.$store.dispatch("fetchProject", item.data.id);
            this.$router.push("/show-project");
        }
    },
    created() {
        this.$store.dispatch("fetchProjects");
    },
    computed: {
        ...mapGetters(["projects", "loadingProjects"])
    }
};
</script>

<style scoped></style>
