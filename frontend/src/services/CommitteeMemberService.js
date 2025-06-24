import axios from '@/plugins/axios.js';

export default {
  /**
   * Get all committee members
   * @returns {Promise} Axios promise
   */
  getAllCommitteeMembers() {
    return axios.get('/committee-members');
  },

  /**
   * Create a new committee member
   * @param {FormData} memberData - Member data including image file
   * @returns {Promise} Axios promise
   */
  createCommitteeMember(memberData) {
    return axios.post('/committee-members', memberData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  },

  /**
   * Get committee members by edition and committee type
   * @param {Number} editionId - Edition ID
   * @param {String} committee - Committee type
   * @returns {Promise} Axios promise
   */
  getMembersByEditionAndCommittee(editionId, committee) {
    return axios.get(`/committee-members/${editionId}/${committee}`);
  },

  /**
   * Update a committee member
   * @param {Number} id - Member ID
   * @param {FormData} memberData - Updated member data
   * @returns {Promise} Axios promise
   */
  updateCommitteeMember(id, memberData) {
    return axios.post(`/committee-members/${id}`, memberData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    });
  },

  /**
   * Delete a committee member
   * @param {Number} id - Member ID
   * @returns {Promise} Axios promise
   */
  deleteCommitteeMember(id) {
    return axios.delete(`/committee-members/${id}`);
  }
};