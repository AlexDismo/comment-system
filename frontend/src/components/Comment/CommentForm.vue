<template>
  <div class="bg-white p-4 rounded shadow-md w-full flex flex-col justify-between">
    <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-3 gap-4">
      <div>
        <label class="block text-gray-700">Name</label>
        <input v-model="name" @input="updateState" class="w-full p-2 border rounded bg-white" type="text" />
      </div>
      <div class="md:col-span-1">
        <label class="block text-gray-700">Email</label>
        <input v-model="email" @input="updateState" class="w-full p-2 border rounded bg-white" type="email" />
      </div>
      <div class="md:col-span-1">
        <label class="block text-gray-700">URL</label>
        <input v-model="url" @input="updateState" class="w-full p-2 border rounded bg-white" type="url" />
      </div>
      <div class="md:col-span-3">
        <label class="block text-gray-700">Title</label>
        <input v-model="title" @input="updateState" class="p-2 border rounded bg-white" type="text" />
      </div>
      <div class="md:col-span-1">
        <label class="block text-gray-700">Content</label>
        <span v-if="!isContentValid" class="text-red-500">Content is required and should not exceed 500 characters</span>
        <QuillForm @content-changed="handleContentChanged" ref="quillForm" toolbarId="comment-toolbar"/>
      </div>
      <div class="md:col-span-3">
        <label class="block text-gray-700">Attachments</label>
        <input ref="fileInput" type="file" @change="onFileChange" class="p-2 border rounded" multiple />
        <span v-if="!areFilesValid" class="text-red-500">Files are required. Only JPG, GIF, PNG, TXT are allowed. Maximum 5 files. Each file should be less than 100KB</span>
      </div>
      <button class="bg-blue-500 text-white p-2 rounded">Submit</button>
    </form>
  </div>
</template>

<script>

import QuillForm from "@/components/Additional/QuillForm.vue";
import commentService from "@/services/commentService";

export default {
  components: {QuillForm},
  data() {
    return {
      name: '',
      email: '',
      url: '',
      content: '',
      title: '',
      files: [],
      isContentValid: true,
      areFilesValid: true,
    };
  },
  created() {
     const userInput =  localStorage.getItem('user_input')
      if(userInput){
        const {author_name, author_email, author_url} = JSON.parse(userInput);
        this.name = author_name;
        this.email = author_email;
        this.url = author_url;
      }
      this.updateState();
  },
  methods: {
    updateState() {
      this.$store.commit('setAuthorName', this.name);
      this.$store.commit('setAuthorEmail', this.email);
      this.$store.commit('setAuthorUrl', this.url);
    },
    onFileChange(e) {
      this.files = Array.from(e.target.files);
    },
    async submit() {
      const response = await commentService.submitForm(this.name, this.email, this.url, this.content, this.files, null, this.title);
      if (response) {
        this.$emit('submitComment', response);
        this.$refs.quillForm.clearContent();
        this.content = '';
        this.title = '';
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