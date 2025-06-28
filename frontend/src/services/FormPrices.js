import axios from '@/plugins/axios';


const priceService = {
    // Get all prizes
    async getPrizes() {
        try {
            const response = await axios.get(`/form-prices`);
            return response.data.data;
        } catch (error) {
            console.error('Error fetching prizes:', error);
            throw error;
        }
    },

    // Get prizes by edition
    async getPrizesByEdition(editionId) {
        try {
            const response = await axios.get(`/form-prices/edition/${editionId}`);
            return response.data.data;
        } catch (error) {
            console.error('Error fetching prizes by edition:', error);
            throw error;
        }
    },

    // Add a new prize
    async addPrize(formData) {
        try {
            const response = await axios.post(`/form-prices`, formData);
            return response.data.data;
        } catch (error) {
            console.error('Error adding prize:', error);
            throw error;
        }
    },

    // Update a prize
    async updatePrize(id, formData) {
        try {
            const response = await axios.put(`/form-prices/${id}`, formData);
            return response.data.data;
        } catch (error) {
            console.error('Error updating prize:', error);
            throw error;
        }
    },

    // Delete a prize
    async deletePrize(id) {
        try {
            await axios.delete(`/form-prices/${id}`);
            return true;
        } catch (error) {
            console.error('Error deleting prize:', error);
            throw error;
        }
    }
};

export default priceService;