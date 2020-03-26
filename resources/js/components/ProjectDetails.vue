<template>
    <v-simple-table>
        <template v-slot:default>
            <thead>
                <tr>
                    <th class="text-left">Item</th>
                    <th class="text-left">Valor</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in items" :key="item.name">
                    <td>{{ item.name }}</td>
                    <td>{{ item.calories }}</td>
                </tr>
            </tbody>
        </template>
    </v-simple-table>
</template>

<script>
export default {
    name: "ProjectDetails",
    props: ["items"]
};
</script>

<style></style>
