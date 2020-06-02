<template>
  <v-select
    v-if="ifConditional?ifConditional:true"
    clearable
    v-model="fieldValue"
    :items="items"
    :label="label"
    :placeholder="placeholder"
    outlined
    focus
    :error-messages="fieldValueErrors"
    @input="$v.fieldValue.$touch()"
    @blur="$v.fieldValue.$touch()"
  >
    <template v-if="defaultOption" v-slot:default>
      <v-list-item>
        <v-list-item-content>
          <v-list-item-title>{{defaultOption}}</v-list-item-title>
        </v-list-item-content>
      </v-list-item>
    </template>
  </v-select>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
export default {
  name: "SelectField",
  mixins: [validationMixin],

  validations() {
    var validations = {
      fieldValue: {}
    };
    if (this.required) validations.fieldValue.required = required();
    return validations;
  },
  props: {
    ifConditional: {
      type: Boolean
    },
    placeholder: {
      type: String
    },

    items: {
      required: true,
      type: Array
    },
    label: {
      type: String,
      required: true
    },
    required: {
      type: Boolean
    },
    defaultOption: {
      type: String
    }
  },
  computed: {
    fieldValue: {
      get() {
        return this.value;
      },
      set(value) {
        this.$emit("update:value", value);
      }
    },
    fieldValueErrors() {
      const errors = [];
      if (!this.$v.fieldValue.$dirty) return errors;
      this.required &&
        !this.$v.fieldValue.required &&
        errors.push("Campo Obrigatório.");
      return errors;
    }
  }
};
</script>

<style>
</style>
