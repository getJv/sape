<template>
    <!--   <v-row justify="start" align="center">
  <v-col cols="12">-->
    <v-tooltip color="transparent" left>
        <template v-slot:activator="{ on }">
            <v-list-item v-on="on">
                <v-list-item-content>
                    <v-list-item-title>
                        {{ item.data.attributes.created_at | moment }}
                        -
                        {{ item.data.attributes.name }}
                        <BtnEditEvent :item="item" />
                        <v-icon
                            color="red red-lighten-3"
                            small
                            @click="deleteItem(item)"
                            >mdi-delete</v-icon
                        >
                    </v-list-item-title>
                </v-list-item-content>
            </v-list-item>
        </template>
        <span>
            <v-card dark max-width="700px">
                <v-card-title>{{ item.data.attributes.name }}</v-card-title>
                <v-divider></v-divider>
                <v-card-text>
                    <v-row>
                        <v-col cols="12">
                            Criado em:
                            {{ item.data.attributes.created_at | moment }}
                        </v-col>
                        <v-col cols="12">
                            <p class="title">Descrição</p>
                            <span>
                                {{ item.data.attributes.description }}
                            </span>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>
        </span>
    </v-tooltip>
    <!--     </v-col>
  </v-row>-->
</template>
<script>
import moment from "moment";
import BtnEditEvent from "../components/BtnEditEvent";

export default {
    name: "StepCard",
    components: {
        BtnEditEvent
    },
    props: ["item"],
    methods: {
        deleteItem(item) {
            if (!confirm("Deseja realmente excluir este item?")) return;
            this.$store.dispatch("deleteEvent", item.data.id);
        }
    },

    filters: {
        moment: function(date) {
            return moment(date).format("D/M/Y");
        }
    }
};
</script>
