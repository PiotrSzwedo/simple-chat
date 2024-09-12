async function reloadElement(url, elementId){
    try {
        const response = await fetch(url);
        const data = await response.text();
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');

        const targetElement = doc.querySelector(elementId);

        if (!targetElement){
            console.error(`Target element ${element} not found on the page.`);
            return;
        }

        const newContent = targetElement.innerHTML;

        const elementInPage = document.querySelector(elementId);
        if (!elementInPage){
            console.error(`Target element ${element} not found on the page.`);
            return;
        }

        elementInPage.innerHTML = newContent;
    } catch (error) {
        return;
    }
}

function setHtml(element, data) {
    try {
        const parser = new DOMParser();
        const doc = parser.parseFromString(data, 'text/html');
        // Ensure that the element exists in the parsed HTML
        const newContentElement = doc.querySelector(element);
        if (!newContentElement) {
            console.error(`Element ${element} not found in the provided data.`);
            return;
        }
        // Ensure the target element exists on the page
        const targetElement = document.querySelector(element);
        if (!targetElement) {
            console.error(`Target element ${element} not found on the page.`);
            return;
        }

        // Safely replace the inner HTML
        targetElement.innerHTML = newContentElement.innerHTML;

    } catch (error) {
        console.error('Error in setHtml function:', error);
    }
}
    