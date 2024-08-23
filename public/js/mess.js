function show(id) {
    // change url whiteout reload page
    const newUrl = '/chat/conversation/' + id;
    history.pushState({ path: newUrl }, '', newUrl);

    // reload only div, that have class = chatValue
    fetch(newUrl)
    .then(response => response.text())  // Get page as html
    .then(data => {
        // Prase html to DOM (Document Object Model) object
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');

        // find html for div
        const newContent = doc.querySelector('.chatValue').innerHTML;

        // set content of div 
        document.querySelector('.chatValue').innerHTML = newContent;
    })
    .catch(error => console.error('Error', error));
}