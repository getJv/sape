<template>
    <div>
        <v-text-field
            v-model="fieldValue"
            :label="label"
            outlined
            v-money="money"
            :error-messages="fieldValueErrors"
            @input="$v.fieldValue.$touch()"
            @blur="$v.fieldValue.$touch()"
        ></v-text-field>
    </div>
</template>

<script>
import { validationMixin } from "vuelidate";
import { required, minLength, maxLength } from "vuelidate/lib/validators";
import { VMoney } from "v-money";

export default {
    name: "NumberField",
    directives: { money: VMoney },
    data() {
        return {
            money: {
                decimal: ",",
                thousands: ""
                //prefix: "R$ ",
                //suffix: " #",
                //precision: 2,
                //masked: false /* doesn't work with directive */
            }
        };
    },
    props: {
        required: {
            type: Boolean
        },
        label: {
            required: true,
            type: String
        },
        value: {
            required: true
        },
        min: {
            type: Number
        },
        max: {
            type: Number
        }
    },
    mixins: [validationMixin],
    validations() {
        var validations = {
            fieldValue: {}
        };
        if (this.required) validations.fieldValue.required = required;
        if (this.min > 0) {
            validations.fieldValue.minLength = minLength(this.min);
        }
        if (this.max > 0) {
            validations.fieldValue.maxLength = maxLength(this.max);
        }
        return validations;
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
            this.min &&
                !this.$v.fieldValue.minLength &&
                errors.push("Mínimo de " + this.min + " caracteres");
            this.max &&
                !this.$v.fieldValue.maxLength &&
                errors.push("Máximo de " + this.max + " caracteres");
            return errors;
        }
    }
};
</script>

<style></style>
