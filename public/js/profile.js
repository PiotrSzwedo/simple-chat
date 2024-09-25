function showPhotoForm(){
    form = document.querySelector(".photoForm");

    if (form.classList.contains("open")){
        form.classList.remove("open");
    }else{
        form.classList.add("open");
    }
}