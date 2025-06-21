import axios from '@/plugins/axios'; // Assuming you have axios configured with your base URL

export default {
    /**
     * Get all important dates
     * @returns {Promise} Axios promise
     */
    getAllDates() {
        return axios.get('/important-dates');
    },

    /**
     * Create a new important date
     * @param {Object} dateData 
     * @returns {Promise} Axios promise
     */
    createDate(dateData) {
        return axios.post('/important-dates', dateData);
    },

    /**
     * Get a specific important date by ID
     * @param {Number} id 
     * @returns {Promise} Axios promise
     */
    getDate(id) {
        return axios.get(`/important-dates/${id}`);
    },

    /**
     * Update an important date
     * @param {Number} id 
     * @param {Object} dateData 
     * @returns {Promise} Axios promise
     */
    updateDate(id, dateData) {
        return axios.put(`/important-dates/${id}`, dateData);
    },

    /**
     * Delete an important date
     * @param {Number} id 
     * @returns {Promise} Axios promise
     */
    deleteDate(id) {
        return axios.delete(`/important-dates/${id}`);
    },

    /**
     * Get important dates by edition ID
     * @param {Number} editionId 
     * @returns {Promise} Axios promise
     */
    getDatesByEdition(editionId) {
        return axios.get(`/important-dates/edition/${editionId}`);
    },

    /**
     * Get the latest upcoming important date for countdown
     * @param {Number} editionId 
     * @returns {Promise} Axios promise
     */
    getLatestCountdownDate(editionId) {
        return axios.get(`/important-dates/latest-countdown/${editionId}`);
    }
};