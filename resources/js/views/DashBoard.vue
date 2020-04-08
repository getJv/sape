<template>
  <div>
    <p>Dashboard View</p>
    <!-- <component
      :is="type"
      ref="campo_do_nome"
      :label="label"
      :required="required"
      :min="min"
      :max="max"
      :value.sync="value"
    />-->
  </div>
  <!-- $refs[hashName].$v.$invalid -->
</template>

<script>
import integer from "../components/formInputs/IntegerField";
export default {
  name: "DashBoard",
  components: {
    integer
  },
  computed: {},
  data() {
    return {
      value: 123,
      type: "integer",
      label: "Campo do nome",
      min: 3,
      max: 7,
      required: true
    };
  }
};
</script>

<style>
</style>
