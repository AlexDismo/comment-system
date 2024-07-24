<template>
  <div class="h-screen flex items-center justify-center bg-gray-100">
    <div class="w-full h-full p-6 bg-white shadow rounded-3xl">
      <h2 class="text-xl font-semibold text-gray-700 mb-4">Comments</h2>
      <p class="text-sm text-gray-500 font-normal mb-6">Leave your comment below.</p>
      <CommentSection />
    </div>
  </div>
</template>
п
<script>
import CommentSection from "@/components/Comment/CommentSection.vue";

export default {
  components: {
    CommentSection,
  },
};
</script>

<style scoped>
</style>