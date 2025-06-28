import axios from '@/plugins/axios.js';

export default {
    getAllNotifications() {
        return axios.get('/api/notifications');
    },
    getNotificationById(id) {
        return axios.get(`/api/notifications/${id}`);
    },
    createNotification(data) {
        return axios.post('/api/notifications', data);
    },
    updateNotification(id, data) {
        return axios.put(`/api/notifications/${id}`, data);
    },
    deleteNotification(id) {
        return axios.delete(`/api/notifications/${id}`);
    }
};