<template>
  <div class="">
    <div class="mb-4">
      <label for="sort_by" class="block text-sm font-medium text-gray-700">Sort by</label>
      <select id="sort_by" v-model="sortBy" @change="resetAndLoadMore" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        <option value="author_name">Author Name</option>
        <option value="author_email">Author Email</option>
        <option value="created_at">Date</option>
      </select>
    </div>
    <div class="mb-4">
      <label for="order" class="block text-sm font-medium text-gray-700">Order</label>
      <select id="order" v-model="order" @change="resetAndLoadMore" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
      </select>
    </div>
    <CommentItem v-for="(comment, index) in comments" :key="index" :comment="comment" @submitReply="submitReply"/>
    <button @click="loadMore" class="text-blue-500">Load More</button>
  </div>
</template>

<script>
import commentService from '../../services/commentService';
import CommentItem from "@/components/Comment/CommentItem.vue";

export default {
  components: {CommentItem},
  data() {
    return {
      comments: [],
      page: 1,
      sortBy: 'created_at',
      order: 'desc',
    };
  },
  methods: {
    async loadMore() {
      const response = await commentService.getComments(this.page, this.sortBy, this.order);
      this.comments = [...this.comments, ...response.data];
      this.page += 1;
    },
    async resetAndLoadMore() {
      this.page = 1;
      this.comments = [];
      await this.loadMore();
    },
    submitReply(reply) {
      this.$emit('submitReply', reply);
    },
    addComment(comment) {
      this.comments.unshift(comment.data)
    },
  },
  created() {
    this.loadMore();
  },
};
</script>