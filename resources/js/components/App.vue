<template>
    <v-app id="inspire">
        <Sidebar :drawer.sync="drawer" />

        <v-app-bar flat app clipped-left color="blue-grey darken-2">
            <v-app-bar-nav-icon color="white" @click.stop="drawer = !drawer" />
            <v-toolbar-title class="white--text"> SAPE</v-toolbar-title>
        </v-app-bar>

        <v-content class="grey lighten-5">
            <v-container>
                <v-row align="baseline" justify="start">
                    <v-col>
                        <router-view></router-view>
                    </v-col>
                </v-row>
            </v-container>
            <BtnConfiguration />
        </v-content>

        <v-footer app color="blue-grey darken-2">
            <span>
                <small class="caption white--text">
                    &copy; {{ new Date().getFullYear() }} — EPL Sistema para
                    acompanhamento de projetos estratégicos | Versão do sistema:
                    {{ numVersion }}
                </small>
            </span>
        </v-footer>
    </v-app>
</template>

<script>
import Sidebar from "./Sidebar";
import BtnConfiguration from "../components/BtnConfiguration";
const version = JSON.stringify(require("../../../package.json").version);

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
        numVersion: version.replace('"', "").replace('"', ""),
        bottomNav: "recent",
        drawer: true
    }),
    created() {
        this.$vuetify.theme.dark = false;
    }
};
</script>

<style></style>
