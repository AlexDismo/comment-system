<template>
  <div class="max-w-[155]">
    <div :id="toolbarId" class="quill-sel">
      <button class="ql-link"></button>
      <button class="ql-code"></button>
      <button class="ql-italic"></button>
      <button class="ql-customStrong">S</button>
    </div>
    <QuillEditor ref="myQuillEditor" v-model:content="internalContent" :options="this.options" :toolbar="`#${toolbarId}`"/>
  </div>
</template>

<script>
import {Quill, QuillEditor} from '@vueup/vue-quill'
import '@vueup/vue-quill/dist/vue-quill.snow.css';
const Inline = Quill.import('blots/inline');
const Link = Quill.import('formats/link');
class BoldBlot extends Inline {
  static blotName = 'bold';
  static tagName = 'b';
}
class ItalicBlot extends Inline {
  static blotName = 'italic';
  static tagName = 'i';
}
class CustomStrongBlot extends Inline {
  static blotName = 'customStrong';
  static tagName = 'strong';
  static create(value) {
    CustomStrongBlot._value = value;
    return super.create();
  }
}
class PlainLink extends Link {
  static blotName = 'link';
  static tagName = 'a';
  static create(value) {
    let node = super.create(value);
    node.removeAttribute('target');
    node.removeAttribute('rel');
    return node;
  }
}
Quill.register(BoldBlot);
Quill.register(ItalicBlot);
Quill.register(CustomStrongBlot);
Quill.register(PlainLink);

export default {
  components: {QuillEditor},
  props: ['content', 'toolbarId'],
  data() {
    return {
      internalContent: this.content,
      options: {
        modules: {
          clipboard: {
            allowed: {
              tags: ['a', 'b', 'strong', 'i', 'p', 'br'],
              attributes: ['href']
            },
            keepSelection: true,
            substituteBlockElements: true,
            magicPasteLinks: true,
          },
        },
        placeholder: 'Compose an epic...',
        theme: 'snow'
      }
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
    updateContent() {
      let htmlContent = this.$refs.myQuillEditor.getHTML();
      htmlContent = htmlContent.replace('<span class="ql-cursor">﻿</span>', '');
      this.$emit('content-changed', htmlContent);
    },
    clearContent() {
      this.$refs.myQuillEditor.setHTML('');
    },
  },
  watch: {
    internalContent() {
      this.updateContent();
    },
  },
};
</script>
<style scoped>
.quill-sel button {
  display: flex;
  justify-content: center;
  align-items: center;
}
</style>