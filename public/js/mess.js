const ws = new WebSocket("ws://" + window.location.hostname +":8080");
let isWsOpen = false;
let myId = document.getElementById("myIdInput").value;
let userScrolled = false;

window.addEventListener('load', function() {
    scrollToElementIfNotScrolled();
});

ws.onopen = () => {
    isWsOpen = true;
    sendToWsServer(myId, "", "registerId", myId);
};

ws.onmessage = (jsonData) => {
    const data = JSON.parse(jsonData.data);
    const con = document.querySelector(".con");

    if (data.sender === document.getElementById("userInputId") || data.sender === myId){
        const styleClass = (data.sender === myId) ? "my" : "";
        
        if (data.type === "message"){
            con.innerHTML += `<div class='messDiv ${styleClass}'><div class='mess ${styleClass}'> ${data.message}</div></div>`;
        }else if (data.type = "attachment"){
            con.innerHTML += `<div class='messDiv ${styleClass}'><div class='mess ${styleClass}'><img src="${data.message}"></div></div>`;
        }
    }else{
        reloadElement(window.location.href, "#users");
    }

    scrollToElementIfNotScrolled();
};

ws.onerror = (error) => { 
    console.error('WebSocket error:', error);
};

ws.onclose = () => {
    console.log('WebSocket connection closed.');
    isWsOpen = false;
};

function sendToWsServer(userId, message, type, sender) {
    if (isWsOpen) {
        ws.send(JSON.stringify({
            type: type,
            userId: userId,
            message: message,
            sender: sender
        }));
    } else {
        console.error("WebSocket connection is not open.");
    }
}


async function show(id) {
    const newUrl = `/chat/conversation/${id}`;
    history.pushState({ path: newUrl }, '', newUrl);

    await reloadElement(newUrl, "#chatValue");
    await reloadElement(newUrl, "#users");
    showMessages();
    scrollToElementIfNotScrolled();
}

async function sendMsg() {
    const messageInput = document.getElementById('messageInput');
    const userIdInput = document.getElementById("userInputId");
    const fileInputElement = document.getElementById('fileInput');
    const fileInput = fileInputElement.files[0];

    if (isWsOpen) {
        if (fileInput) {
            const patch = await uploadFile(fileInput);
            if (patch) {
                sendToWsServer(userIdInput.value, patch, "attachment", myId);
            }
        } else {
            sendToWsServer(userIdInput.value, messageInput.value, "message", myId);
        }
    } else {
        console.error("Cannot send message. WebSocket connection is not open.");
    }

    fileInputElement.value = ''; 
    messageInput.value = '';
    messageInput.focus();
}

async function uploadFile(file) {
    const formData = new FormData();
    formData.append('file', file);

    try {
        const response = await fetch('/php/upload_file.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            const text = await response.text();
            return text;
        } else {
            console.error(`File upload failed with status: ${response.status}`);
            return "";
        }
    } catch (error) {
        console.error(`File upload error: ${error}`);
        return "";
    }
}

function showMessages() {
    const messageElement = document.querySelector('.messages');
    messageElement.classList.toggle("show");
}

const triggerElement = document.getElementById('showUsers'); 
if (triggerElement) {
    triggerElement.addEventListener('click', showMessages);
}

function scrollToElementIfNotScrolled() {
    const element = document.querySelector('.con');
    
    if (element && !userScrolled) {

        element.scrollTo({
            top: element.scrollHeight, 
            behavior: 'smooth'
        });
    }
}

function handleUserScroll() {
    userScrolled = true;
}