import { createStore } from 'vuex';

export default createStore({
    state: {
        author_name: '',
        author_email: '',
        author_url: '',
    },
    mutations: {
        setAuthorName(state, name) {
            state.author_name = name;
        },
        setAuthorEmail(state, email) {
            state.author_email = email;
        },
        setAuthorUrl(state, url) {
            state.author_url = url;
        },
    },
});