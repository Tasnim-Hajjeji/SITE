import axios from "@/plugins/axios";

const ContactService ={
    async getContacts(){
        try{
            const response = await axios.get('/contacts');
            return response.data;
        }catch(error){
            console.error('error fetching contacts',error);
            throw error;
        }
    },
    async addContact(data){
        try{
            const response= await axios.post('/contacts',data);
            return response.data;
        }catch(error){
            console.error('error fetching contacts',error);
            throw error;
        }
    },
    async deleteContact(id){
        try{
            await axios.delete(`/contacts/${id}`);
            return true;
        }catch(error){
            console.error('error fetching contacts',error);
            throw error;
        }
    },
};
export default ContactService;