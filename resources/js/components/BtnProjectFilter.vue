<template>
    <v-tooltip bottom>
        <template v-slot:activator="{ on }">
            <v-icon
                    small
                    color="green lighten-3"
                    class="pa-1"
                    v-on="on"
                    dark
                    @click="$emit('update:exibirTodos',!exibirTodos)"
                    >{{ exibirIcone }}</v-icon
                >
        </template>
        <span>{{ exibirTooltip }}</span>
    </v-tooltip>
</template>

<script>
export default {
    name: "BtnEditProject",
    props: ["exibirTodos"],
    computed: {
        exibirIcone(){
            if(this.exibirTodos){
               return 'mdi-layers-outline';
            }else{
                return 'mdi-layers-off-outline'
            }
        },
        exibirTooltip(){
            if(!this.exibirTodos){
               return 'Exibir todos';
            }else{
                return 'Exibir apenas ativos'
            }
        }
     }
};
</script>

<style></style>
