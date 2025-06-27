import axios from '@/plugins/axios';


const documentService = {
    // Get all documents
    async getDocuments() {
        try {
            const response = await axios.get(`/documents`);
            return response.data;
        } catch (error) {
            console.error('Error fetching documents:', error);
            throw error;
        }
    },

    // Add a new document
    async addDocument(formData) {
        try {
            const response = await axios.post(`/documents`, formData, {
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            });
            return response.data;
        } catch (error) {
            console.error('Error adding document:', error);
            throw error;
        }
    },

    // Delete a document
    async deleteDocument(id) {
        try {
            await axios.delete(`/documents/${id}`);
            return true;
        } catch (error) {
            console.error('Error deleting document:', error);
            throw error;
        }
    },

    // Get documents by edition
    async getDocumentsByEdition(editionId) {
        try {
            const response = await axios.get(`/documents/edition/${editionId}`);
            return response.data;
        } catch (error) {
            console.error('Error fetching documents by edition:', error);
            throw error;
        }
    }
};

export default documentService;