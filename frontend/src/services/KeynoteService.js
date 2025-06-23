import axios from '@/plugins/axios';


export default {
    /**
     * Get all keynotes
     * @returns {Promise} Axios response
     */
    getAllKeynotes() {
        return axios.get(`/keynotes`);
    },

    /**
     * Get a specific keynote by ID
     * @param {number} id - Keynote ID
     * @returns {Promise} Axios response
     */
    getKeynote(id) {
        return axios.get(`/keynotes/${id}`);
    },

    /**
     * Create a new keynote
     * @param {Object} keynoteData - Keynote data
     * @param {string} keynoteData.description_fr - French description
     * @param {string} keynoteData.description_en - English description
     * @param {string} keynoteData.type - 'speaker' or 'committee'
     * @param {number} keynoteData.speaker_id - Speaker/Committee member ID
     * @param {number} keynoteData.edition_id - Edition ID
     * @returns {Promise} Axios response
     */
    createKeynote(formData) {
        return axios.post(`/keynotes`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    },

    /**
     * Update a keynote
     * @param {number} id - Keynote ID
     * @param {Object} keynoteData - Keynote data
     * @returns {Promise} Axios response
     */
    updateKeynote(id, keynoteData) {
        return axios.post(`/keynotes/${id}`, keynoteData,{
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    },

    /**
     * Delete a keynote
     * @param {number} id - Keynote ID
     * @returns {Promise} Axios response
     */
    deleteKeynote(id) {
        return axios.delete(`/keynotes/${id}`);
    },

    /**
     * Get keynotes by edition ID
     * @param {number} editionId - Edition ID
     * @returns {Promise} Axios response
     */
    getKeynotesByEdition(editionId) {
        return axios.get(`/keynotes/edition/${editionId}`);
    }
}

