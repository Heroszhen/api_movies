import { defineStore } from 'pinia';
import { getToken, fetchGet } from '../service/HttpService.js';

export const usePhotoStore = defineStore('photo', {
    state: () => {
        return {
            photos: [],
            slogan: 'zhen est le plus grand héros du monde.'
        }
    },
    getters: {
        getPhotos() {
            return this.photos;
        }
    },
    actions: {
        async fetchPhotos() {
            console.log(2)
            const token = await getToken();
            let response = await fetchGet('/api/movie_media_objects', token);
            this.photos = response['hydra:member'];console.log(3)
        }
    }
});