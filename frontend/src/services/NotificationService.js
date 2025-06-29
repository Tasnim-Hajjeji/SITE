import axios from '@/plugins/axios.js';

export default {
    getAllNotifications() {
        return axios.get('/notifications');
    },
    getNotificationById(id) {
        return axios.get(`/notifications/${id}`);
    },
    getActivatedNotifs(){
        return axios.get('/notifications/activated')
    },
    createNotification(data) {
        return axios.post('/notifications', data);
    },
    activateNotification(id) {
        return axios.put(`/notifications/${id}/activate`);
    },
    deactivateNotification(id) {
        return axios.put(`/notifications/${id}/deactivate`);
    },
    deleteNotification(id) {
        return axios.delete(`/notifications/${id}`);
    }
};