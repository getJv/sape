<template>
    <v-container fluid >
        <v-row align="center" justify="center">
            <v-col cols="12" md="5">
                <v-card flat outlined>
                    <v-img
                        class="white--text align-end"
                        height="150px"
                        src="/imagens/epl_logo.png"
                    ></v-img>

                    <v-card-text>
                        <form>
                            <v-row align="center" justify="center">
                                <v-col cols="12" md="8">
                                    <userField
                                        label="Usuário de Rede"
                                        required
                                        :value.sync="username"
                                    ></userField>
                                </v-col>
                                <v-col cols="12" md="8">
                                    <userPass
                                        label="Senha"
                                        isPassword
                                        required
                                        :value.sync="password"
                                    ></userPass>
                                </v-col>
                                <v-col cols="12" md="8">
                                    <v-btn
                                        block
                                        text
                                        color="green"
                                        outlined
                                        @click="enviar()"
                                        >Acessar</v-btn
                                    >
                                </v-col>
                            </v-row>
                        </form>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
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

<style>
body {
    background-color: var(--v-accent-lighten2);
  }
</style>

</style>
