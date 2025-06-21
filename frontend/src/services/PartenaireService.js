import axios from '@/plugins/axios.js';

export default {
  /**
   * Get all partners
   * @returns {Promise} Axios promise
   */
  getAllPartenaires() {
    return axios.get('/partenaires');
  },

  /**
   * Create a new partner
   * @param {Object} partenaireData - Partner data including image file
   * @returns {Promise} Axios promise
   */
  createPartenaire(partenaireData) {
    const formData = new FormData();
    Object.keys(partenaireData).forEach(key => {
      if (key === 'image') {
        formData.append('image', partenaireData.image);
      } else {
        formData.append(key, partenaireData[key]);
      }
    });
    
    return axios.post('/partenaires', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  },

  /**
   * Get a specific partner by ID
   * @param {Number} id - Partner ID
   * @returns {Promise} Axios promise
   */
  getPartenaire(id) {
    return axios.get(`/partenaires/${id}`);
  },

  /**
   * Update a partner
   * @param {Number} id - Partner ID
   * @param {Object} partenaireData - Updated partner data
   * @returns {Promise} Axios promise
   */
  updatePartenaire(id, partenaireData) {
    const formData = new FormData();
    Object.keys(partenaireData).forEach(key => {
      if (key === 'image') {
        formData.append('image', partenaireData.image);
      } else {
        formData.append(key, partenaireData[key]);
      }
    });

    return axios.post(`/partenaires/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  },

  /**
   * Delete a partner
   * @param {Number} id - Partner ID
   * @returns {Promise} Axios promise
   */
  deletePartenaire(id) {
    return axios.delete(`/partenaires/${id}`);
  },

  /**
   * Get partners by edition ID
   * @param {Number} editionId - Edition ID
   * @returns {Promise} Axios promise
   */
  getPartenairesByEdition(editionId) {
    return axios.get(`/partenaires/edition/${editionId}`);
  }
};