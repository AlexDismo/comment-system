<template>
  <div>
    <div v-if="nestedComments.length === 0" class="mt-4 ml-4">
      <div class="p-2 bg-gray-200 rounded">
        <p class="text-gray-600">No Comments</p>
      </div>
    </div>
    <div v-else v-for="nestedComment in nestedComments" :key="nestedComment.id" class="mt-4 ml-4">
      <CommentItem :comment="nestedComment" />
    </div>
    <div v-if="isLoading" class="mt-4 ml-4">
      <div class="p-2 bg-gray-200 rounded">
        <p class="text-gray-600">Loading...</p>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    nestedComments: {
      type: Array,
      required: true,
    },
    isLoading: {
      type: Boolean,
      required: true,
    },
  },
};
</script>