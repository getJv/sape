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
      <v-list-item two-line class="px-0">
        <v-list-item-avatar>
          <v-avatar color="indigo" size="36">
            <span class="pa-5 white--text headline">
              {{
              initialUser
              }}
            </span>
          </v-avatar>
        </v-list-item-avatar>

        <v-list-item-content>
          <v-list-item-title>{{ nameUser }}</v-list-item-title>
          <v-list-item-subtitle>
            {{
            user.data.attributes.cargo
            }}
          </v-list-item-subtitle>
        </v-list-item-content>
      </v-list-item>

      <v-divider></v-divider>

      <v-list-item @click="$router.push('/')" link>
        <v-list-item-action>
          <v-icon color="green darken-1">mdi-view-dashboard</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>
            <span>
              Dashboard
              <BtnAddDashBoardItem />
            </span>
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>
      <v-list-item link>
        <v-list-item-action>
          <v-icon color="pink darken-1">mdi-toy-brick-marker-outline</v-icon>
        </v-list-item-action>
        <v-list-item-content>
          <v-list-item-title>
            <span>
              Projetos
              <BtnProjectOrder :activeOrder.sync="activeOrder" />
              <BtnProjectFilter :showAll.sync="showAll" />
              <BtnAddProject :hasSession="true" />
            </span>
          </v-list-item-title>
        </v-list-item-content>
      </v-list-item>

      <span v-if="projects">
        <v-list-item v-for="item in filteredProjects" :key="item.data.id" link>
          <BtnProjectUpDown
            v-if="activeOrder"
            class="mr-2"
            :item="item"
            :lastOrderItem="filteredProjects[filteredProjects.length -1].data.attributes.order"
          />
          <v-list-item-content @click="showProject(item)">
            <v-list-item-title>
              <span>
                {{
                item.data.attributes.name
                }}
              </span>
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
        <div class="pa-2" v-if="activeOrder">
          <v-btn small @click="resetOrder">Ordernar por criação</v-btn>
        </div>
      </span>
    </v-list>

    <template v-slot:append>
      <div class="pa-2">
        <v-btn block @click="logout">Sair</v-btn>
      </div>
    </template>
  </v-navigation-drawer>
</template>

<script>
import BtnAddProject from "./BtnAddProject";
import BtnProjectFilter from "./BtnProjectFilter";
import BtnProjectOrder from "./BtnProjectOrder";
import BtnProjectUpDown from "./BtnProjectUpDown";
import BtnAddDashBoardItem from "./BtnAddDashBoardItem";

import { mapGetters } from "vuex";

export default {
  name: "Sidebar",
  props: ["drawer"],
  components: {
    BtnAddProject,
    BtnProjectFilter,
    BtnProjectOrder,
    BtnProjectUpDown,
    BtnAddDashBoardItem
  },
  data() {
    return {
      showAll: false,
      activeOrder: false
    };
  },
  methods: {
    logout() {
      this.$store.dispatch("logout");
      this.$router.push("/login");
    },
    ocultar() {
      if (!this.$refs.navbar.isActive) {
        this.$emit("update:drawer", false);
      }
    },

    resetOrder() {
      if (
        confirm(
          "Ao confirmar os projetos serão reordenados conforme sua ordem de criação. Esta operação não podera ser desfeita"
        )
      ) {
        this.$store.dispatch("projectResetOrder");
      }
    },

    showProject(item) {
      this.$store.dispatch("fetchProject", item.data.id);
      this.$router.push("/show-project").catch(err => {});
    }
  },
  created() {
    this.$store.dispatch("fetchProjects");
  },
  computed: {
    ...mapGetters(["projects", "loadingProjects", "user"]),
    nameUser() {
      var pieces = this.user.data.attributes.name.split(" ");
      return pieces[0] + " " + pieces[pieces.length - 1];
    },
    initialUser() {
      var pieces = this.user.data.attributes.name.split(" ");
      return (
        pieces[0].substring(0, 1) + pieces[pieces.length - 1].substring(0, 1)
      );
    },

    filteredProjects() {
      if (this.showAll) {
        return this.projects.data;
      } else {
        return this.projects.data.filter(item => item.data.attributes.active);
      }
    }
  }
};
</script>

<style scoped></style>
