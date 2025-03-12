const form = document.querySelector(".disable-btn");

form?.addEventListener('submit', (e) => {
    e.preventDefault();

    const formData = new FormData(form);
    for (let [key, value] of formData.entries()) {
        console.log(`${key}: ${value}`);
    }
    
    fetch(
        '/milleret-inscription',
        {
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        method: 'post',
        body: formData
    })
    .then(function(response) {return response.json();})
    .then((json) => {
        console.log(json)
    })
    .catch(e => { })
});