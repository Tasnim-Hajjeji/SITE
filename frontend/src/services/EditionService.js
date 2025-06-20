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
    async getCurrentEdition() {
       try{
        const response= await axios.get('/editions/current');
        return response;
       }catch(error) {
              console.error("Error fetching current edition:", error);
              throw error;
         }
    },
    getPreviousEditions() {
        return axios.get('/editions/previous');
    },
    
}