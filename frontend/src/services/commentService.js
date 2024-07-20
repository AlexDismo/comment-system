import axios from 'axios';
import validationService from "@/services/validationService";

const API_URL = process.env.VUE_APP_BACKEND_URL;

export default {
    async getComments(page = 1, sortBy = 'created_at', order = 'desc') {
        const response = await axios.get(`${API_URL}api/comment?page=${page}&sort_by=${sortBy}&order=${order}`);
        return response.data;
    },

    async getNestedComments(id, page = 1) {
        const response = await axios.get(`${API_URL}api/comment/${id}?page=${page}`);
        return response.data;
    },

    async postComment(comment) {
        const formData = new FormData();
        Object.keys(comment).forEach(key => {
            if (key === 'files' && comment.files.length > 0) {
                comment.files.forEach((file) => {
                    formData.append('files[]', file);
                });
            } else {
                formData.append(key, comment[key]);
            }
        });
        try {
            const response = await axios.post(`${API_URL}api/comment`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            return response.data;
        } catch (error) {
            if (error.response && error.response.status === 422) {
                console.log(error.response.data);
            } else {
                console.log(error.message);
            }
        }
    },
    async submitForm(author_name, author_email, author_url, content, files, parentId = null, title = null) {
        const errors = [];

        if (!validationService.validateAuthorName(author_name)) {
            errors.push('Author name is invalid');
        }
        if (!validationService.validateAuthorEmail(author_email)) {
            errors.push('Author email is invalid');
        }
        if (author_url.length > 0 && !validationService.validateAuthorUrl(author_url)) {
            errors.push('Author URL is invalid');
        }
        if (content.length > 0 && !validationService.validateContent(content)) {
            errors.push('Content is invalid');
        }
        if (!validationService.validateFiles(files)) {
            errors.push('Files are invalid');
        }

        if (errors.length > 0) {
            alert(errors.join('\n'));
            errors.length = 0;
            return;
        }

        localStorage.setItem('user_input', JSON.stringify({author_name, author_email, author_url}));

        const comment = {
            ...(title && {title: title}),
            author_name,
            author_email,
            content,
            ...(parentId && {parent_id: parentId}),
            ...(author_url && {author_url: author_url}),
            ...(files.length > 0 && {files: files})
        };
        console.log(comment)
        return await this.postComment(comment);
    }
};