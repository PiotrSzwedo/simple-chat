async function show(id) {
    // change url whiteout reload page
    const newUrl = '/chat/conversation/' + id;
    history.pushState({ path: newUrl }, '', newUrl);

    // reload only div, that have id = chatValue
    await reloadElementById(newUrl, "chatValue");
    await reloadElementById(newUrl, "msg");
    showMessages();
}

async function sendMsg(link) {
    // Download form data
    const form = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');

    // Generate form
    const formData = new FormData(form);

    // Send data
    await fetch(link, {
        method: 'POST',
        body: formData
    });
    messageInput.value = '';

    await reloadElementById(window.location.href, "chatValue");

    const elementsFromForm = form.querySelectorAll('input, textarea, select');

    elementsFromForm.forEach(field => {
        if (field.type === 'checkbox' || field.type === 'radio') {
            field.checked = false;
        } else {
            field.value = '';
        }
    });
}

async function reloadElementById(url, elementId){
    try {
        const response = await fetch(url);
        const data = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        const newContent = doc.getElementById(elementId).innerHTML;
        document.getElementById(elementId).innerHTML = newContent;
    } catch (error) {
        console.error('Error', error);
    }
}

const triggerElement = document.getElementById('showUsers'); // Replace with the actual selector

function showMessages(){
    const messageElement = document.querySelector('.messages');

    if (messageElement.classList.contains("show")){
        messageElement.classList.remove("show");
    } else {
        messageElement.classList.add("show");
    }
}