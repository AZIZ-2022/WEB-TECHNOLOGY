document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.querySelector('form'); 
    const usernameInput = document.getElementById('ousername'); 
    const passwordInput = document.getElementById('opassword'); 
    const errorAlert = document.querySelector('.alert.error'); 

    if (!loginForm || !usernameInput || !passwordInput || !errorAlert) {
        console.error("Form or input elements not found!");
        return;
    }

    loginForm.addEventListener('submit', function (event) {
        let errors = [];

        
        errorAlert.textContent = '';
        errorAlert.style.display = 'none';

        
        if (usernameInput.value.trim() === '') {
            errors.push('Username cannot be empty!');
        } else if (!validateEmail(usernameInput.value.trim())) {
            errors.push('Invalid email format for username!');
        }

       
        if (passwordInput.value.trim() === '') {
            errors.push('Password cannot be empty!');
        } else if (passwordInput.value.trim().length < 6) {
            errors.push('Password must be at least 6 characters long!');
        }

       
        if (errors.length > 0) {
            event.preventDefault();
            errorAlert.innerHTML = errors.join('<br>'); 
            errorAlert.style.display = 'block';
        }
    });

    
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});
