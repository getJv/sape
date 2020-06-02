<template>
  <span>
    <v-tooltip v-if="item.data.attributes.order != 1" bottom>
      <template v-slot:activator="{ on }">
        <v-icon
          small
          color="blue lighten-3"
          class
          v-on="on"
          dark
          @click="$store.dispatch('projectFieldMoveUp',item)"
        >mdi-arrow-up-bold</v-icon>
      </template>
      <span>Mover para cima</span>
    </v-tooltip>
    <v-tooltip v-if="lastOrderItem != item.data.attributes.order" bottom>
      <template v-slot:activator="{ on }">
        <v-icon
          small
          color="blue lighten-3"
          class
          v-on="on"
          dark
          @click="$store.dispatch('projectFieldMoveDown',item)"
        >mdi-arrow-down-bold</v-icon>
      </template>
      <span>Mover para baixo</span>
    </v-tooltip>
  </span>
</template>

<script>
export default {
  name: "BtnProjectFieldUpDown",
  mounted() {},
  props: {
    item: {
      required: true,
      type: Object
    },
    lastOrderItem: {
      type: Number
    }
  }
};
</script>

<style></style>
