<template>
  <div>
    <button @click="toggler = !toggler">
      Open the lightbox.
    </button>

    <FsLightbox
        :toggler="toggler"
        :sources="[]"
    />
  </div>
</template>

<script>
import FsLightbox from "fslightbox-vue/v3";

export default {
  components: { FsLightbox },
  data() {
    return {
      toggler: false
    }
  }
};
</script>