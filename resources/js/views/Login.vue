<template>
  <div>
    <video poster="/imagens/video-bg.jpg" autoplay loop id="videoBackground" muted plays-inline>
      <source src="/imagens/video-home.mp4" type="video/mp4" />ssss
    </video>

    <v-container fluid>
      <v-row align="center" justify="center">
        <v-col cols="12" md="5">
          <v-card flat outlined>
            <v-img class="white--text align-end" height="150px" src="/imagens/epl_logo.png"></v-img>

            <v-card-text>
              <form>
                <v-row align="center" justify="center">
                  <v-col cols="12" md="8">
                    <userField label="Usuário de Rede" required :value.sync="username"></userField>
                  </v-col>
                  <v-col cols="12" md="8">
                    <userPass label="Senha" isPassword required :value.sync="password"></userPass>
                  </v-col>
                  <v-col cols="12" md="8">
                    <v-btn block text color="green" outlined @click="enviar()">Acessar</v-btn>
                  </v-col>
                </v-row>
              </form>
            </v-card-text>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import userField from "./../components/formInputs/TextField";
import userPass from "./../components/formInputs/TextField";
export default {
  name: "Login",
  components: {
    userField,
    userPass
  },
  mounted() {
    this.updateSession();
  },

  methods: {
    updateSession() {
      if (this.$store.getters.isAuthenticated) {
        axios.defaults.headers.common[
          "Authorization"
        ] = `Bearer ${this.$store.getters.user.data.attributes.access_token}`;
        this.$router.push(this.$route.query.redirect || "/dashboard");
      }
    },
    enviar() {
      axios
        .post("/api/login", {
          username: this.username,
          password: this.password
        })
        .then(res => {
          this.$store.commit("setUser", res.data);
          this.updateSession();
          this.$store.dispatch("fetchProjects");

          this.$router.push(this.$route.query.redirect || "/");
        })
        .catch(err => console.log(err.data));
    }
  },
  data() {
    return {
      username: "",
      password: ""
    };
  }
};
</script>

<style scoped>
#videoHeader {
  height: 100vh;
}

#videoBackground {
  position: absolute;
  right: 0;
  bottom: 0;
  min-width: 100%;
  min-height: 100%;
  width: auto;
  height: auto;
  z-index: 0;
}
@media (min-aspect-ratio: 16/9) {
  #videoBackground {
    width: 100%;
    height: auto;
  }
}
@media (max-aspect-ratio: 16/9) {
  #videoBackground {
    width: auto;
    height: 100%;
  }
}
@media (max-width: 767px) {
  #videoBackground {
    display: none;
  }
  body {
    background: url("/imagens/video-bg.jpg");
    background-size: cover;
  }
}
</style>
