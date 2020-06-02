<template>
    <v-dialog
        v-model="dialog"
        fullscreen
        hide-overlay
        transition="dialog-bottom-transition"
    >
        <template v-slot:activator="{ on }">
            <v-icon small color="green" v-on="on">mdi-eye</v-icon>
        </template>
        <v-card>
            <v-toolbar dark color="primary">
                <v-btn icon dark @click="dialog = false">
                    <v-icon>mdi-close</v-icon>
                </v-btn>
                <v-toolbar-title>Documento: {{ pdfName }}</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-toolbar-items>
                    <v-btn dark text @click="download">Download</v-btn>
                </v-toolbar-items>
            </v-toolbar>

            <pdf ref="myPdfComponent" :src="pdfUrl"></pdf>
        </v-card>
    </v-dialog>
</template>

<script>
import pdf from "vue-pdf";

export default {
    name: "app",
    components: {
        pdf
    },
    props: {
        pdfUrl: {
            requeired: true,
            type: String
        },
        pdfName: {
            requeired: true,
            type: String
        }
    },
    methods: {
        download() {
            axios({
                url: this.pdfUrl,

                method: "GET",

                responseType: "blob"
            }).then(response => {
                var fileURL = window.URL.createObjectURL(
                    new Blob([response.data])
                );

                var fileLink = document.createElement("a");

                fileLink.href = fileURL;

                fileLink.setAttribute("download", this.pdfName);

                document.body.appendChild(fileLink);

                fileLink.click();
            });
        }
    },

    data() {
        return {
            dialog: false
        };
    }
};
</script>

<style></style>
