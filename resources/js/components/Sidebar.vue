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
            <v-list-item two-line :class="miniVariant && 'px-0'">
                <v-list-item-avatar>
                    <img src="https://randomuser.me/api/portraits/men/81.jpg" />
                </v-list-item-avatar>

                <v-list-item-content>
                    <v-list-item-title>Jhonatan Morais</v-list-item-title>
                    <v-list-item-subtitle>Assistente I</v-list-item-subtitle>
                </v-list-item-content>
            </v-list-item>

            <v-divider></v-divider>

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
                            <BtnProjectFilter :exibirTodos.sync="exibirTodos" />
                            <BtnAddProject :hasSession="true" />
                        </span>
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>

            <span v-if="projects">
                <v-list-item
                    v-for="item in filteredProjects"
                    :key="item.data.id"
                    link
                >
                    <v-list-item-content @click="showProject(item)">
                        <v-list-item-title>{{
                            item.data.attributes.name
                        }}</v-list-item-title>
                    </v-list-item-content>
                </v-list-item>
            </span>
        </v-list>

        <template v-slot:append>
            <div class="pa-2">
                <v-btn block>Sair</v-btn>
            </div>
        </template>
    </v-navigation-drawer>
</template>

<script>
import BtnAddProject from "./BtnAddProject";
import BtnProjectFilter from "./BtnProjectFilter";

import { mapGetters } from "vuex";

export default {
    name: "Sidebar",
    props: ["drawer"],
    components: {
        BtnAddProject,
        BtnProjectFilter
    },
    data() {
        return {
            exibirTodos: false
        };
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
        ...mapGetters(["projects", "loadingProjects"]),
        filteredProjects() {
            if (this.exibirTodos) {
                return this.projects.data;
            } else {
                return this.projects.data.filter(
                    item => item.data.attributes.active
                );
            }
        }
    }
};
</script>

<style scoped></style>
