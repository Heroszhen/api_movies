import { createApp } from 'petite-vue'

createApp({
    users: [],
    //init
    mounted() {
        this.getUsers();
    },
    async getUsers() {
        try {
            let response = await fetch("https://randomuser.me/api/?gender=female&results=20");
            
        } catch(e) {}
    }
}).mount()