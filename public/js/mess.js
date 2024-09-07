const ws = new WebSocket("ws://localhost:8080");
myId = document.getElementById("myIdInput").value;

// WebSocket code

ws.onopen = () => {
    sendToWsServer(myId, "", "registerId", myId);
}

function sendToWsServer(userId, message, type, sender) {
    ws.send(JSON.stringify({
        type: type,
        userId: userId,
        message: message,
        sender: sender
    }));
}

ws.onmessage = (jsonData) => {
    const data = JSON.parse(jsonData.data);
    if (data.type === 'message') {
        console.log(`Message from user ${data.userId}: ${data.message}`);
    }
};

ws.onerror = (error) => {
    console.error('WebSocket error:', error);
};

ws.onclose = () => {
    console.log('WebSocket connection closed.');
};

// code to show and hide html

async function show(id) {
    const newUrl = '/chat/conversation/' + id;
    history.pushState({ path: newUrl }, '', newUrl);

    await reloadElement(newUrl, "#chatValue");
    await reloadElement(newUrl, "#users");
    showMessages();
}

async function sendMsg() {
    const form = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');
    const userIdInput = document.getElementById("userInputId");

    sendToWsServer(userIdInput.value, messageInput.value, "message", myId)

    messageInput.value = '';
    
    const elementsFromForm = form.querySelectorAll('input, textarea, select');
    
    elementsFromForm.forEach(field => {
        if (field.type === 'checkbox' || field.type === 'radio') {
            field.checked = false;
        } else {
            field.value = '';
        }
    });
    
    messageInput.focus();
}

const triggerElement = document.getElementById('showUsers'); 

function showMessages(){
    const messageElement = document.querySelector('.messages');

    if (messageElement.classList.contains("show")){
        messageElement.classList.remove("show");
    } else {
        messageElement.classList.add("show");
    }
}