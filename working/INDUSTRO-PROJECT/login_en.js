const inputUsername = document.getElementById("usernameInput");
const inputPassword = document.getElementById("passwordInput");
const botonLogin = document.getElementById("loginButton");
const selectLang = document.getElementById("langSelect");

botonLogin.addEventListener("click", function(event) {
    event.preventDefault();
    
    const username = inputUsername.value;
    const password = inputPassword.value;
    
    if (username === "" || password === "") {
        alert("Please fill in the fields correctly");
        return;
    } else {
        alert("Successful login!");
    }
});

selectLang.addEventListener("change", function() {
    const selectedLanguage = selectLang.value;
    
    if (selectedLanguage === "Español") {
        window.location.href = "login_es.html";
    }
});