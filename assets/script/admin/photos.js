import { createApp, ref, nextTick, onMounted, reactive, computed, watch } from 'vue/dist/vue.esm-browser.js';
import { getToken, fetchGet } from '../service/HttpService.js';
import { createPinia, storeToRefs } from 'pinia';
import { usePhotoStore } from '../store/pinia.photo.js';

const app = createApp({
  setup() {
    const message = ref('Hello vue 3!');
    let identity = ref(null);
    let divHandsome = ref();
    let divChinese = ref();
    let form = ref(true);
    let photos2 = reactive([]);
    let photoStore = usePhotoStore();
    const { photos } = storeToRefs(photoStore);
 

    onMounted(async () => {
      const token = await getToken();
      let response = await fetchGet('/api/movie_media_objects', token);
      Object.assign(photos2, response['hydra:member']);
      
       photoStore.fetchPhotos();
    })

    async function moveDiv(str) {
      if (str === 'div_handsome')await animateDiv(divHandsome.value)
      else await animateDiv(divChinese.value);
    }

    async function animateDiv(dom) {
      let random = (min, max) => min + Math.floor(Math.random() * (max));
      dom.style.top = `${random(60, window.innerHeight - 100)}px`;
      dom.style.left = `${random(1, window.innerWidth - 200)}px`;
      dom.style.position = 'absolute';
      await nextTick();
    }

    function submit() {
      let resetDom = (dom) => { 
        dom.style.position = 'static';
      }
      if (identity.value === 'hero') {
        resetDom(divHandsome.value);
        resetDom(divChinese.value);
        form.value = false;
        window.setTimeout(() => {
          document.querySelector('iframe').remove();
        }, 7000);
      }
    }
    
    return {message, identity, submit, moveDiv, divHandsome, divChinese, form, photoStore, photos, photos2}
  }
});
app.use(createPinia());
app.mount('#app');