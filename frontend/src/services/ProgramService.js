import axios from '@/plugins/axios.js';


export default  {
  /**
   * Get all programs
   * @returns {Promise} Axios response
   */
  getAllPrograms() {
    return axios.get(`/programs`);
  },

  /**
   * Get a single program by ID
   * @param {string|number} id - Program ID
   * @returns {Promise} Axios response
   */
  getProgram(id) {
    return axios.get(`/programs/${id}`);
  },

  /**
   * Create a new program
   * @param {Object} programData - Program data
   * @returns {Promise} Axios response
   */
  createProgram(programData) {
    return axios.post(`/programs`, programData);
  },

  /**
   * Update a program
   * @param {string|number} id - Program ID
   * @param {Object} programData - Program data
   * @param {Array} [intervenantIds] - Array of intervenant IDs
   * @returns {Promise} Axios response
   */
  updateProgram(id, programData, intervenantIds = null) {
    const data = { ...programData };
    if (intervenantIds !== null) {
      data.intervenant_ids = intervenantIds;
    }
    return axios.put(`/programs/${id}`, data);
  },

  /**
   * Delete a program
   * @param {string|number} id - Program ID
   * @returns {Promise} Axios response
   */
  deleteProgram(id) {
    return axios.delete(`/programs/${id}`);
  },

  /**
   * Get programs by edition ID
   * @param {string|number} editionId - Edition ID
   * @returns {Promise} Axios response
   */
  getProgramsByEdition(editionId) {
    return axios.get(`/programs/edition/${editionId}`);
  },

  /**
   * Get programs by specific date
   * @param {string} date - Date in YYYY-MM-DD format
   * @returns {Promise} Axios response
   */
  getProgramsByDate(date) {
    return axios.get(`/programs/by-date`, {
      params: { date }
    });
  },
}

