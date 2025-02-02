document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.querySelector('form');
    const usernameInput = document.getElementById('ousername');
    const passwordInput = document.getElementById('opassword');
    const errorAlert = document.querySelector('.alert.error');

    loginForm.addEventListener('submit', function (event) {
        let isValid = true;
        let errorMessage = '';

        
        if (errorAlert) {
            errorAlert.textContent = '';
            errorAlert.style.display = 'none';
        }

        
        if (usernameInput.value.trim() === '') {
            isValid = false;
            errorMessage = 'Username cannot be empty!';
        } else if (!validateEmail(usernameInput.value.trim())) {
            isValid = false;
            errorMessage = 'Invalid email format for username!';
        }

        
        if (passwordInput.value.trim() === '') {
            isValid = false;
            errorMessage = 'Password cannot be empty!';
        }

       
        if (!isValid) {
            event.preventDefault();
            if (errorAlert) {
                errorAlert.textContent = errorMessage;
                errorAlert.style.display = 'block';
            }
        }
    });

   
    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
});