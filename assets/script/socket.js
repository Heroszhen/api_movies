import $ from 'jquery';

const socket = new WebSocket("ws://localhost:8080");
let canSend = false;
let socketId, response;

$(() => {
    $('#btn-message').on('click', () => {
        socket.send($("#input-message").val());
        $("#input-message").val("");
    });
});


socket.addEventListener("open", (event) => {
    console.log("websocket open");
    canSend = true;
    $("#input-message").prop("disabled", false);
    $("#btn-message").prop("disabled", false);
});

socket.addEventListener("close", (event) => {
    console.log('websocket close')
});

socket.addEventListener("error", (event) => {
    console.log("WebSocket error: ", event);
});

// Listen for messages
socket.addEventListener("message", (event) => {
    response = JSON.parse(event.data);
    if (response['type'] === 'id') {
        socketId = response['data'];
    } else {
        if ("content" in document.createElement("template")) {
            let template = document.querySelector("#messageTemplate");
            let clone = document.importNode(template.content, true);
            let article = clone.querySelector('article');
            article.querySelector('.id').textContent = response['data']['clientId'];
            article.querySelector('.created').textContent = response['data']['created'];
            article.querySelector('.content').textContent = response['data']['message'];
            if (response['data']['clientId'] === socketId) {
                article.classList.add('right');
            }
            $('.wrap-chat').append(article);
        }
    }
});
