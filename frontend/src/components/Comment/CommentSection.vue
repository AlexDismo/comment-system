<template>
  <div class="w-full h-full flex flex-col gap-52">
    <CommentForm @submitComment="addComment" />
    <CommentList ref="commentList" />
  </div>
</template>

<script>
import CommentForm from './CommentForm.vue';
import CommentList from './CommentList.vue';

export default {
  components: {
    CommentForm,
    CommentList,
  },
  methods: {
    addComment(comment) {
      this.$refs.commentList.addComment(comment);
    },
  },
};
</script>