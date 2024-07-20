export default {
    validateAuthorName(name) {
        return name && name.length <= 100;
    },
    validateAuthorEmail(email) {
        const emailRegex = /^[\w-]+(\.[\w-]+)*@([\w-]+\.)+[a-zA-Z]{2,7}$/;
        return email && emailRegex.test(email);
    },
    validateAuthorUrl(url) {
        try {
            new URL(url);
            return true;
        } catch (_) {
            return false;
        }
    },
    validateContent(content) {
        if (!content || content.trim() === '') {
            return false;
        }
        if (content.length < 1 || content.length > 500) {
            return false;
        }
        return content.trim() !== '<p><br></p>';
    },
    validateFile(file) {
        if (!file) {
            return false;
        }
        const validFormats = ['image/jpeg', 'image/gif', 'image/png', 'text/plain'];
        const isFormatValid = validFormats.includes(file.type);
        const isSizeValid = file.type !== 'text/plain' || file.size <= 100 * 1024; // 100KB
        return isFormatValid && isSizeValid;
    },
    validateFiles(files) {
        if (!files || files.length > 5) {
            return false;
        }
        for (let file of files) {
            if (!this.validateFile(file)) {
                return false;
            }
        }
        return true;
    },
};