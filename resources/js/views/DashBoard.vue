<template>
  <div>
    <p>Dashboard View</p>
    <component
      :is="type"
      ref="campo_do_nome"
      :label="label"
      :required="required"
      :min="min"
      :max="max"
      :value.sync="value"
    />
  </div>
  <!-- $refs[hashName].$v.$invalid -->
</template>

<script>
import integerField from "../components/formInputs/IntegerField";
export default {
  name: "DashBoard",
  components: {
    integerField
  },
  computed: {},
  data() {
    return {
      value: 123,
      type: "integerField",
      label: "Campo do nome",
      min: 3,
      max: 7,
      required: true
    };
  }
};
</script>

<style></style>
