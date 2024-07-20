<template>
  <button @click="$emit('toggle')" class="bg-blue-500 text-white p-2 rounded text-xs">
    <span v-if="isExpanded">Hide Nested Comments</span>
    <span v-else>Show Nested Comments</span>
  </button>
</template>

<script>
export default {
  props: {
    isExpanded: {
      type: Boolean,
      required: true,
    },
  },
};
</script>