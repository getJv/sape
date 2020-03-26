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
        </v-content>

        <v-footer app>
            <span>&copy; 2019</span>
        </v-footer>
    </v-app>
</template>

<script>
import Nav from "./Nav";
import Sidebar from "./Sidebar";

export default {
    name: "App",

    components: {
        Nav,
        Sidebar
    },
    props: {
        source: String
    },
    data: () => ({
        drawer: true
    }),
    created() {
        this.$vuetify.theme.dark = true;
    }
};
</script>

<style></style>
