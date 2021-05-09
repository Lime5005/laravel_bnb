export default {
    data() {
        return {
            errors: null
        }
    },
    methods: {
        errorFor(field) {
            // This error is not null, and this error field exist, return this errors
            return this.errors !== null && this.errors[field] ? this.errors[field] : null
        }
    }
}