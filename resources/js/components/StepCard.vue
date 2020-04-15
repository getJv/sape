<template>
    <v-row justify="start" align="center">
        <v-col cols="12">
            <v-tooltip color="transparent" left>
                <template v-slot:activator="{ on }">
                    <span v-on="on">
                        {{ item.data.attributes.created_at | moment }}
                        -
                        {{ item.data.attributes.name }}
                    </span>
                </template>
                <span>
                    <v-card dark>
                        <v-card-title>
                            {{ item.data.attributes.name }}
                        </v-card-title>
                        <v-divider></v-divider>
                        <v-card-text>
                            <v-row>
                                <v-col cols="12"
                                    >Criado em:
                                    {{
                                        item.data.attributes.created_at | moment
                                    }}
                                </v-col>
                                <v-col cols="12">Responsável:</v-col>
                                <v-col cols="12">
                                    <p class="title">
                                        Descrição
                                    </p>
                                    {{
                                        item.data.attributes.description
                                    }}</v-col
                                >
                            </v-row>
                        </v-card-text>
                    </v-card>
                </span>
            </v-tooltip>
        </v-col>
    </v-row>
</template>
<script>
import moment from "moment";

export default {
    name: "StepCard",
    props: ["item"],

    filters: {
        moment: function(date) {
            return moment(date).format("D/M/Y");
        }
    }
};
</script>
