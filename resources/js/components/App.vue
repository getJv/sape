<template>
    <v-app :class="isAuthenticated ? '' : 'bgTransparent'">
        <Sidebar :drawer.sync="drawer" v-if="isAuthenticated" />
        <v-app-bar
            v-if="isAuthenticated"
            :elevation="1"
            app
            clipped-left
            color="blue-grey lighten-5"
        >
            <v-app-bar-nav-icon
                color="blue-grey darken-5"
                @click.stop="drawer = !drawer"
            />
            <v-toolbar-title>SAPE</v-toolbar-title>
            <v-spacer></v-spacer>
            <v-img
                class="text--center"
                :src="imageHeader"
                max-width="250"
            ></v-img>
        </v-app-bar>

        <v-content :class="isAuthenticated ? 'grey lighten-5' : ''">
            <router-view></router-view>
            <BtnConfiguration v-if="isAuthenticated" />
        </v-content>

        <v-footer app color="blue-grey lighten-5">
            <span>
                <small class="caption">
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
import { mapGetters } from "vuex";
export default {
    name: "App",

    components: {
        Sidebar,
        BtnConfiguration
    },
    computed: {
        ...mapGetters(["isAuthenticated"])
    },
    props: {
        source: String
    },
    data: () => ({
        numVersion: version.replace('"', "").replace('"', ""),
        bottomNav: "recent",
        drawer: true,
        imageHeader: "/imagens/epl_logo.png"
    }),
    created() {
        this.$vuetify.theme.dark = false;
    }
};
</script>

<style>
.bgTransparent {
    background-color: transparent !important;
}
</style>
