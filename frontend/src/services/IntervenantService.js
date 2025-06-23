import axios from "@/plugins/axios.js";

export default {
    getAllIntervenants() {
        return axios.get("/intervenants");
    },
    getIntervenantById(id) {
        return axios.get(`/intervenants/${id}`);
    },
    getIntervenantsByEdition(editionId) {
        return axios.get(`/intervenants/edition/${editionId}`);
    },
    createIntervenant(intervenant) {
        return axios.post("/intervenants", intervenant);
    },
    updateIntervenant(id, intervenant) {
        return axios.post(`/intervenants/${id}`, intervenant);
    },
    deleteIntervenant(id) {
        return axios.delete(`/intervenants/${id}`);
    }
}