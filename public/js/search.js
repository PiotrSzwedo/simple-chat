document.getElementById("searchInput").addEventListener("input", search);

async function search(){
    const form = document.getElementById('searchForm');
    
    const formData = new FormData(form);

    try {
        // Send data
        const response = await fetch("/search", {
            method: 'post',
            body: formData
        });

        const htmlContent = await response.text();

        // Update the specific part of the page
        setHtml(".searchResult", htmlContent);

    } catch (error) {
        console.error('Error:', error);
    }
}