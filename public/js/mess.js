async function show(id) {
    // change url whiteout reload page
    const newUrl = '/chat/conversation/' + id;
    history.pushState({ path: newUrl }, '', newUrl);

    // reload only div, that have id = chatValue
    await reloadElement(newUrl, "#chatValue");
    await reloadElement(newUrl, ".messages");
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
    
    await reloadElement(window.location.href, "#chatValue");
    await reloadElement(window.location.href, ".messages");
    
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

const triggerElement = document.getElementById('showUsers'); // Replace with the actual selector

function showMessages(){
    const messageElement = document.querySelector('.messages');

    if (messageElement.classList.contains("show")){
        messageElement.classList.remove("show");
    } else {
        messageElement.classList.add("show");
    }
}