async function reloadElement(url, elementId){
    try {
        const response = await fetch(url);
        const data = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        const newContent = doc.querySelector(elementId).innerHTML;
        document.querySelector(elementId).innerHTML = newContent;
    } catch (error) {
        return;
    }
}