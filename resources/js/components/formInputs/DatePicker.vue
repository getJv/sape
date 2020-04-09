<template>
  <div>
    <v-menu
      v-model="menu"
      :close-on-content-click="false"
      transition="scale-transition"
      offset-y
      max-width="290px"
      min-width="290px"
    >
      <template v-slot:activator="{ on }">
        <v-text-field
          :value="computedDateFormatted"
          :label="label"
          persistent-hint
          min="12-09-2019"
          prepend-icon="mdi-calendar"
          readonly
          outlined
          v-on="on"
          :error-messages="dateErrors"
          @input=" $v.date.$touch()"
          @blur="$v.date.$touch()"
        ></v-text-field>
      </template>
      <v-date-picker
        v-model="date"
        no-title
        @input="menu = false"
        locale="pt-br"
        :min="minPeriodoInicio"
      ></v-date-picker>
    </v-menu>
  </div>
</template>
<script>
import { validationMixin } from "vuelidate";
import { required } from "vuelidate/lib/validators";
import moment from "moment";
export default {
  props: {
    label: {
      type: String,
      required: true
    },
    min: {
      type: String
    }
  },
  mixins: [validationMixin],
  validations: {
    date: { required }
  },
  created() {
    console.log();
  },
  data: vm => ({
    date: null, //new Date().toISOString().substr(0, 10),
    dateFormatted: vm.formatDate(new Date().toISOString().substr(0, 10)),
    menu: false
  }),
  computed: {
    computedDateFormatted() {
      return this.formatDate(this.date);
    },
    minPeriodoInicio() {
      if(this.min){
        return moment(this.min, "DD/MM/YYYY").format("YYYY-MM-DD");
      }
      return moment().format("YYYY-MM-DD");
    },
    dateErrors() {
      const errors = [];
      if (!this.$v.date.$dirty) return errors;
      !this.$v.date.required && errors.push("Campo Obrigatório.");
      return errors;
    }
  },
  watch: {
    date() {
      this.dateFormatted = this.formatDate(this.date);
      this.$emit("update:value", this.dateFormatted);
      this.dateFormatted = null;
    }
  },
  methods: {
    formatDate(date) {
      if (!date) return null;

      const [year, month, day] = date.split("-");
      return `${day}/${month}/${year}`;
    }
  }
};
</script>