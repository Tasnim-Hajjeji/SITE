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
        return axios.post("/intervenants", intervenant,{
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    },
    updateIntervenant(id, intervenant) {
        return axios.post(`/intervenants/${id}`, intervenant,{
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });
    },
    deleteIntervenant(id) {
        return axios.delete(`/intervenants/${id}`);
    },
    
}