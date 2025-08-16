document.addEventListener("DOMContentLoaded", function(){

    const show_passwords = document.getElementById("show_passwords");
    const password = document.getElementById("password");
    
    show_passwords.addEventListener("change", function(){
        let type = this.checked ? "text" : "password";  // Ternary operator => If checkbox is checked then make the type of password 
        password.type = type;                           // text otherwise mkeep it as password
    });

});