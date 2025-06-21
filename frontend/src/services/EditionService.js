import  axios from '@/plugins/axios.js';
// const apiClient = axios.create({
//     baseURL: 'http://localhost:8000/api/', 
//     withCredentials: false,
//     headers: {
//         Accept: 'application/json',
//         'Content-Type': 'application/json'
//     }
// });
export default {
  /**
   * Get all editions
   * @returns {Promise} Axios promise
   */
  getAllEditions() {
    return axios.get('/editions');
  },

  /**
   * Create a new edition
   * @param {Object} editionData - Edition data including images
   * @returns {Promise} Axios promise
   */
  createEdition(editionData) {
    
    // const formData = new FormData();
    
    // // Append regular fields
    // Object.keys(editionData).forEach(key => {
    //   if (key === 'images') {
    //     // Handle multiple images
    //     editionData.images.forEach(image => {
    //       formData.append('images[]', image);
    //     });
    //   } else {
    //     formData.append(key, editionData[key]);
    //   }
    // });

    return axios.post('/editions', editionData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  },

  /**
   * Get a specific edition by ID
   * @param {Number} id - Edition ID
   * @returns {Promise} Axios promise
   */
  getEdition(id) {
    return axios.get(`/editions/${id}`);
  },

  /**
   * Update an edition
   * @param {Number} id - Edition ID
   * @param {Object} editionData - Updated edition data
   * @returns {Promise} Axios promise
   */
  updateEdition(id, editionData) {
    // const formData = new FormData();
    
    // // Append regular fields
    // Object.keys(editionData).forEach(key => {
    //   if (key === 'images') {
    //     // Handle multiple images
    //     editionData.images.forEach(image => {
    //       formData.append('images[]', image);
    //     });
    //   } else {
    //     formData.append(key, editionData[key]);
    //   }
    // });

    return axios.post(`/editions/${id}`, editionData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  },

  /**
   * Delete an edition
   * @param {Number} id - Edition ID
   * @returns {Promise} Axios promise
   */
  deleteEdition(id) {
    return axios.delete(`/editions/${id}`);
  },

  /**
   * Add images to an edition
   * @param {Number} id - Edition ID
   * @param {Array} images - Array of image files
   * @returns {Promise} Axios promise
   */
  addImages(id, images) {
    const formData = new FormData();
    images.forEach(image => {
      formData.append('images[]', image);
    });

    return axios.post(`/editions/${id}/images`, formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  },

  /**
   * Remove an image from an edition
   * @param {Number} id - Edition ID
   * @param {Number} imageIndex - Index of the image to remove
   * @returns {Promise} Axios promise
   */
  removeImage(id, imageIndex) {
    return axios.delete(`/editions/${id}/images/${imageIndex}`);
  },

  /**
   * Get the current edition
   * @returns {Promise} Axios promise
   */
  getCurrentEdition() {
    return axios.get('/editions/current');
  },

  /**
   * Get previous editions
   * @returns {Promise} Axios promise
   */
  getPreviousEditions() {
    return axios.get('/editions/previous');
  }
};