import axios from '@/plugins/axios.js';

export default {
  /**
   * Get all sponsors
   * @returns {Promise} Axios promise
   */
  getAllSponsors() {
    return axios.get('/sponsors');
  },

  /**
   * Create a new sponsor
   * @param {Object} sponsorData - Sponsor data including logo file
   * @returns {Promise} Axios promise
   */
  createSponsor(sponsorData) {
    // const formData = new FormData();
    // Object.keys(sponsorData).forEach(key => {
    //   if (key === 'logo') {
    //     formData.append('logo', sponsorData.logo);
    //   } else {
    //     formData.append(key, sponsorData[key]);
    //   }
    // });
    
    return axios.post('/sponsors', sponsorData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  },

  /**
   * Get a specific sponsor by ID
   * @param {Number} id - Sponsor ID
   * @returns {Promise} Axios promise
   */
  getSponsor(id) {
    return axios.get(`/sponsors/${id}`);
  },

  /**
   * Update a sponsor
   * @param {Number} id - Sponsor ID
   * @param {Object} sponsorData - Updated sponsor data
   * @returns {Promise} Axios promise
   */
  updateSponsor(id, sponsorData) {
    const formData = new FormData();
    Object.keys(sponsorData).forEach(key => {
      if (key === 'logo') {
        formData.append('logo', sponsorData.logo);
      } else {
        formData.append(key, sponsorData[key]);
      }
    });

    return axios.post(`/sponsors/${id}`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  },

  /**
   * Delete a sponsor
   * @param {Number} id - Sponsor ID
   * @returns {Promise} Axios promise
   */
  deleteSponsor(id) {
    return axios.delete(`/sponsors/${id}`);
  },

  /**
   * Get sponsors by edition ID
   * @param {Number} editionId - Edition ID
   * @returns {Promise} Axios promise
   */
  getSponsorsByEdition(editionId) {
    return axios.get(`/sponsors/edition/${editionId}`);
  }
};