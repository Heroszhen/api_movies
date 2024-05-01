// import 'vue/dist/vue.global.js';
import { createApp, ref, nextTick, onMounted, reactive } from 'vue/dist/vue.esm-browser.js';
import { getToken, fetchGet } from '../service/HttpService.js';

createApp({
  setup() {
    const message = ref('Hello vue 3!');
    let identity = ref(null);
    let divHandsome = ref();
    let divChinese = ref();
    let form = ref(true);
    let photos = reactive([]);

    onMounted(async () => {
      const token = await getToken();
      let response = await fetchGet('/api/movie_media_objects', token);
      Object.assign(photos, response['hydra:member']);
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
        }, 10000);
      }
    }
    
    return {message, identity, submit, moveDiv, divHandsome, divChinese, form}
  }
}).mount('#app')

