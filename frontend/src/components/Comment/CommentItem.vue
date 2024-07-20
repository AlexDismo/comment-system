<template>
  <div class="mb-12">
    <div class="p-4 mb-4 bg-white rounded-lg shadow-lg w-full overflow-hidden" :style="{ marginLeft: comment.parent_id ? '20px' : '0', backgroundColor: comment.parent_id ? '#f3f3f3' : '#fff' }">
      <div class="flex items-start space-x-4">
        <img :src="`https://avatar.iran.liara.run/public/${comment.author_name}`" alt="User avatar" class="w-12 h-12 rounded-full">
        <div>
          <h3 class="text-2xl font-bold text-gray-900 break-all">{{ comment.author_name }}</h3>
          <p class="mt-2 text-gray-700 text-base break-all" v-html="comment.content" style="word-break: break-word;"></p>
          <a :href="comment.author_url" class="mt-2 text-blue-500 text-sm md:text-base">{{ comment.author_url }}</a>
          <div class="flex flex-wrap gap-3">
            <div @click="toggler = !toggler" v-for="imageFile in imageFiles" :key="imageFile.path" class="w-12 h-12">
              <img :src="`${this.API_URL}storage/${imageFile.path}`" alt="Comment image" class="w-full h-full object-cover" />
            </div>
          </div>
          <FsLightbox
              initialAnimation="example-initial-animation"
              :toggler="toggler"
              :sources="this.imageFiles.map(file => `${this.API_URL}api/${file.path}`)"
          />
          <div v-for="textFile in textFiles" :key="textFile.path">
            <a :href="`${this.API_URL}storage/${textFile.path}`" download class="text-blue-800 cursor-pointer border-b-2">Linked text file</a>
          </div>
        </div>
      </div>
      <p v-if="comment.parent_id && comment.parent_content" class="mt-2 text-gray-500">...{{ comment.parent_content.slice(0, 50) }}...</p>
      <button @click="showReplyForm = !showReplyForm" class="mt-2 bg-blue-500 text-white p-2 rounded text-xs">Reply</button>
      <ReplyForm :show="showReplyForm" :parentId="comment.id" @submitReply="submitReply" @replyCreated="addReply"/>
    </div>
    <ToggleCommentsButton :isExpanded="isExpanded" @toggle="toggleNestedComments" />
    <transition name="nested" :duration="550">
      <div v-if="isExpanded && nestedComments" class="outer">
        <div class="inner">
          <NestedCommentsList :nestedComments="nestedComments" :isLoading="isLoading" />
          <LoadMoreButton v-if="isExpanded && nestedComments && nestedComments.length > 0" @loadMore="loadMoreNestedComments" />
        </div>
      </div>
    </transition>
  </div>
</template>
<style scoped>
/* rules that target nested elements */
.nested-enter-active .inner,
.nested-leave-active .inner {
  transition: all 0.3s ease-in-out;
}

.nested-enter-from .inner,
.nested-leave-to .inner {
  transform: translateY(-30px);
  opacity: 0;
}
.nested-enter-active .inner {
  transition-delay: 0.25s;
}
</style>
<script>
import { Transition } from 'vue';
import commentService from "@/services/commentService";
import ToggleCommentsButton from "@/components/UI/ToggleCommentsButton.vue";
import NestedCommentsList from "@/components/Comment/NestedCommentsList.vue";
import LoadMoreButton from "@/components/UI/LoadMoreButton.vue";
import ReplyForm from "@/components/UI/ReplyForm.vue";
import FsLightbox from "fslightbox-vue/v3";

export default {
  components: {
    ReplyForm,
    ToggleCommentsButton,
    NestedCommentsList,
    LoadMoreButton,
    Transition,
    FsLightbox
  },
  props: {
    comment: {
      type: Object,
      required: true,
    },
  },
  emits: ['submitReply'],
  data() {
    return {
      nestedComments: null,
      nestedCommentsPage: 1,
      isLoading: false,
      isExpanded: false,
      showReplyForm: false,
      parent_id: this.comment.id,
      toggler: false,
      API_URL: process.env.VUE_APP_BACKEND_URL,
    };
  },
  computed: {
    imageFiles() {
      return this.comment.files ? this.comment.files.filter(file => this.isImage(file.path)) : [];
    },
    textFiles() {
      return this.comment.files ? this.comment.files.filter(file => !this.isImage(file.path)) : [];
    },
  },
  methods: {
    isImage(path) {
      return /\.(jpg|png|gif)$/.test(path);
    },
    submitReply(reply) {
      this.$emit('submitReply', reply);
    },
    async toggleNestedComments() {
      this.isExpanded = !this.isExpanded;
      if (this.isExpanded && this.nestedComments === null) {
        await this.loadNestedComments();
      }
    },
    async loadNestedComments() {
      this.isLoading = true;
      try {
        const response = await commentService.getNestedComments(this.comment.id, this.nestedCommentsPage);
        const newNestedComments = response.data;
        if (Array.isArray(newNestedComments)) {
          this.nestedComments = [...this.nestedComments || [], ...newNestedComments];
          this.nestedCommentsPage++;
        }
      } catch (error) {
        console.error('Error loading nested comments:', error);
      } finally {
        this.isLoading = false;
      }
    },
    loadMoreNestedComments() {
      this.loadNestedComments();
    },
    addReply(reply) {
      if(this.isExpanded && this.nestedComments){
        this.nestedComments.unshift(reply.data)
      }else{
        this.toggleNestedComments()
      }

    },
  },
};
</script>