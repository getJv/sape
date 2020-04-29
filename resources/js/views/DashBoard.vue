<template>
    <v-container fluid>
        <v-row align="start" justify="start">
            <v-col cols="12" md="3">
                <v-card class="mx-auto" max-width="344" outlined>
                    <v-card-text>
                        <div class="subtitle mb-4">
                            Projetos gerenciados
                        </div>
                        <div class="display-3 text-center">
                            {{ $store.getters.projects.data.length }}
                        </div>
                    </v-card-text>
                </v-card>
            </v-col>
        </v-row>
    </v-container>
</template>

<script>
export default {
    name: "DashBoard",
    components: {},
    computed: {},
    data() {
        return {};
    }
};
</script>

<style></style>
