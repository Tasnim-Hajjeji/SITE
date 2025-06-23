import axios from '@/plugins/axios.js';


export default {
  /**
   * Get all participants
   * @returns {Promise} Axios response
   */
  getAllParticipants() {
    return axios.get(`/participants`);
  },

  /**
   * Get a single participant by ID
   * @param {string|number} id - Participant ID
   * @returns {Promise} Axios response
   */
  getParticipant(id) {
    return axios.get(`/participants/${id}`);
  },

  /**
   * Create a new participant
   * @param {Object} participantData - Participant data
   * @param {File} [recuPaie] - Payment receipt file
   * @returns {Promise} Axios response
   */
  createParticipant(participantData, recuPaie = null) {
    const formData = new FormData();
    
    // Append all participant data to formData
    Object.keys(participantData).forEach(key => {
      formData.append(key, participantData[key]);
    });
    
    // Append the file if it exists
    if (recuPaie) {
      formData.append('recu_paie', recuPaie);
    }

    return axios.post(`/participants`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  },

  /**
   * Update a participant
   * @param {string|number} id - Participant ID
   * @param {Object} participantData - Participant data
   * @param {File} [recuPaie] - Payment receipt file
   * @returns {Promise} Axios response
   */
  updateParticipant(id, participantData, recuPaie = null) {
    const formData = new FormData();
    
    // Append all participant data to formData
    Object.keys(participantData).forEach(key => {
      formData.append(key, participantData[key]);
    });
    
    // Append the file if it exists
    if (recuPaie) {
      formData.append('recu_paie', recuPaie);
    }

    return axios.post(`/participants/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      },
      params: {
        '_method': 'PUT' // Laravel's way to handle PUT via POST
      }
    });
  },

  /**
   * Delete a participant
   * @param {string|number} id - Participant ID
   * @returns {Promise} Axios response
   */
  deleteParticipant(id) {
    return axios.delete(`/participants/${id}`);
  },

   /**
   * Get participants by edition ID
   * @param {string|number} editionId - Edition ID
   * @returns {Promise} Axios response
   */
   getParticipantsByEdition(editionId) {
    return axios.get(`/participants/edition/${editionId}`);
  }
}

