(() => {
    const addButton = document.getElementById('add-girl');
    const girlContainer = document.getElementById('girlFriends');
    const prototype = document.getElementById('man_girlFriends');

    const labels = document.querySelectorAll('label');
    const girlFriendsLabel = Array.from(labels).find(label => label.textContent.trim().includes('Girl friends'));
    girlFriendsLabel?.classList.add('d-none')

    girlContainer.querySelectorAll("div[id^='man_girlFriends_']").forEach(div => {
        addDeleteBtn(div.parentElement);
    })

    addButton.addEventListener('click', () => {
        const newForm = prototype.dataset.prototype.replaceAll(/__name__/g, girlContainer.querySelectorAll("div[id^='man_girlFriends_']").length);
        
        const div = document.createElement('div');
        div.classList.add('girl', 'mb-4');
        div.innerHTML = newForm;

        girlContainer.appendChild(div);
        addDeleteBtn(div);
    });
})();

function addDeleteBtn(wrap) {
    const btn = document.createElement("button");
    btn.setAttribute('class', 'btn btn-danger');
    btn.setAttribute('type', 'button');
    btn.textContent = "Supprimer";
    wrap.querySelector("div[id^='man_girlFriends_']").append(btn);

    btn.addEventListener('click', () => {
        wrap.remove();
    })
}