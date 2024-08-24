function show(id) {
    // change url whiteout reload page
    const newUrl = '/chat/conversation/' + id;
    history.pushState({ path: newUrl }, '', newUrl);

    // reload only div, that have id = chatValue
    reloadElementById(newUrl, "chatValue");
}

function sendMsg(link) {
    // Download form data
    const form = document.getElementById('messageForm');
    const messageInput = document.getElementById('messageInput');

    // Generate form
    const formData = new FormData(form);

    // Send data
    fetch(link, {
        method: 'POST',
        body: formData
    })
    messageInput.value = '';

    reloadElementById(link, "chatValue");

    const elementsFromForm = form.querySelectorAll('input, textarea, select');

    elementsFromForm.forEach(field => {
        if (field.type === 'checkbox' || field.type === 'radio') {
            field.checked = false;
        } else {
            field.value = '';
        }
    });
}

function reloadElementById(url, elementId){
    fetch(url)
    .then(response => response.text())  // Get page as html
    .then(data => {
        // Prase html to DOM (Document Object Model) object
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');

        // find html for div
        const newContent = doc.getElementById(elementId).innerHTML;
        // set content of div 
        document.getElementById(elementId).innerHTML = newContent;
    })
    .catch(error => console.error('Error', error));
}
