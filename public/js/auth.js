function showPassword(parentDiv) {
    if (parentDiv) {
        var passwordInputs = parentDiv.querySelectorAll('input[type="password"], input[type="text"]');

        passwordInputs.forEach(function (input) {
            input.type = input.type === 'password' ? 'text' : 'password';
        });
    } else {
        console.error('Parent element not found.');
    }
}

function switchPanel(){
    var forms = document.getElementById("auth-container").querySelectorAll("form");

    if (forms){
        forms.forEach(function (form) {
            if (form.classList.contains("open")) {
                form.classList.remove("open");
                form.classList.add("close");
            }else{
                form.classList.remove("close");
                form.classList.add("open");
            }
        });
    }
}