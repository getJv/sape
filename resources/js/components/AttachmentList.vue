<template>
  <v-card outlined color="transparent">
    <v-card-text>
      <div class="subtitle font-weight-black">Documentos da fase</div>

      <div v-for="attach in project.links.attachments.data" :key="attach.data.id">
        <v-list-item v-if="attach.data.attributes.status_id == step">
          <v-list-item-content>
            <v-list-item-title style="cursor:pointer" @click="download(attach)">
              <v-icon
                small
                v-if="attach.data.attributes.mime_type == 'application/pdf'"
                color="red red-lighten-3"
              >mdi-file-pdf-box-outline</v-icon>
              <v-icon small v-else color="green green-lighten-3">mdi-file-image-outline</v-icon>
              {{ attach.data.attributes.original_filename }}
            </v-list-item-title>
          </v-list-item-content>
        </v-list-item>
      </div>

      <div v-if="!hasAttachments" class="caption">Esta fase ainda não possui documentos</div>
    </v-card-text>
  </v-card>
</template>

<script>
import { mapGetters } from "vuex";
export default {
  name: "AttachmentList",
  props: {
    step: {
      required: true,
      type: Number
    }
  },
  computed: {
    ...mapGetters(["project"]),
    hasAttachments() {
      return this.project.links.attachments.data.find(
        item => item.data.attributes.status_id == this.step
      );
    }
  },
  methods: {
    download(item) {
      axios({
        url: "api/attachments/download/" + item.data.id,

        method: "GET",

        responseType: "blob"
      }).then(response => {
        var fileURL = window.URL.createObjectURL(new Blob([response.data]));

        var fileLink = document.createElement("a");

        fileLink.href = fileURL;

        fileLink.setAttribute(
          "download",
          item.data.attributes.original_filename
        );

        document.body.appendChild(fileLink);

        fileLink.click();
      });
    }
  }
};
</script>

<style>
</style>
