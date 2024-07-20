import { createApp } from 'vue';
import App from './App.vue';
import CommentItem from './components/Comment/CommentItem.vue';
import store from './store/store.js';

import './assets/css/index.css';

const app = createApp(App);

app.component('CommentItem', CommentItem);
app.use(store);

app.mount('#app');