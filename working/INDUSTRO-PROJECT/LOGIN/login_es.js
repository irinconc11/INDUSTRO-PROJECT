const inputUsername = document.getElementById("usernameInput");
const inputPassword = document.getElementById("passwordInput");
const botonLogin = document.getElementById("loginButton");
const selectLang = document.getElementById("langSelect");

botonLogin.addEventListener("click", function(event) {
    event.preventDefault();
    
    const username = inputUsername.value;
    const password = inputPassword.value;
    
    if (username === "" || password === "") {
        alert("Por favor completa los campos correctamente");
        return;
    } else {
        alert("¡Inicio de sesión exitoso!");
    }
});

selectLang.addEventListener("change", function() {
    const selectedLanguage = selectLang.value;
    
    if (selectedLanguage === "Inglés") {
        window.location.href = "login.html";
    }
})