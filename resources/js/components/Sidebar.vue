<template>
    <div class="w-1/6 bg-white p-4 border-r border-gray-400">
        <h2 class="font-bold text-2xl tracking-tight">Projetos</h2>
        <ul class="list-none" v-if="!loadingProjects">
            <li class="mt-2" v-for="item in projects" :key="item.data.id">
                {{ item.data.attributes.name }}
            </li>
        </ul>
    </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
    name: "Sidebar",
    created() {
        this.$store.dispatch("fetchProjects");
    },
    computed: {
        ...mapGetters(["projects", "loadingProjects"])
    }
};
</script>

<style scoped></style>
