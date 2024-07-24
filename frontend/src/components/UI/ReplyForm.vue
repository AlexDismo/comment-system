<template>
  <div v-if="show" class="mt-4 bg-white p-4 rounded shadow-md w-full">
    <form @submit.prevent="submit" class="grid grid-cols-3 gap-4">
      <div class="col-span-3">
        <QuillForm @content-changed="handleContentChanged" ref="quillForm" :toolbarId="toolbarId"/>
        <span v-if="!isContentValid" class="text-red-500">Content is required and should not exceed 500 characters</span>
      </div>
      <div class="col-span-3">
        <input type="file" ref="fileInput" @change="onFileChange" class="w-full p-2 border rounded" multiple />
        <span v-if="!areFilesValid" class="text-red-500">Files are required. Only JPG, GIF, PNG, TXT are allowed. Maximum 5 files. Each file should be less than 100KB</span>
      </div>
      <div class="col-span-3">
        <button class="bg-blue-500 text-white p-2 rounded">Submit</button>
      </div>
    </form>
  </div>
</template>

<script>
import commentService from "@/services/commentService";
import QuillForm from "@/components/Additional/QuillForm.vue";

export default {
  components: {QuillForm},
  props: {
    show: {
      type: Boolean,
      required: true,
    },
    parentId: {
      type: Number,
      required: true,
    },
  },
  data() {
    return {
      content: '',
      files: [],
      isContentValid: true,
      areFilesValid: true,
      toolbarId: this.generateRandomId(),
    };
  },
  methods: {
    generateRandomId() {
      return 'id' + Math.random().toString(36).substring(2, 15);
    },
    onFileChange(e) {
      this.files = Array.from(e.target.files);
    },
    async submit() {
      const author_name = this.$store.state.author_name;
      const author_email = this.$store.state.author_email;
      const author_url = this.$store.state.author_url;

      const response = await commentService.submitForm(author_name, author_email, author_url, this.content, this.files, this.parentId);
      if (response) {
        this.$emit('replyCreated', response);
        this.$refs.quillForm.clearContent();
        this.content = '';
        this.files = [];
        this.$refs.fileInput.value = '';
      }
    },
    handleContentChanged(htmlContent) {
      this.content= htmlContent;
    },
  },
};
</script>
