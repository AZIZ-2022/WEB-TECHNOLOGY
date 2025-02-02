document.addEventListener('DOMContentLoaded', function () {
    const profileForm = document.querySelector('form');
    const currentPasswordInput = document.getElementById('current_password');
    const newUsernameInput = document.getElementById('new_username');
    const newPasswordInput = document.getElementById('new_password');
    const errorAlert = document.querySelector('.alert.error');

    profileForm.addEventListener('submit', function (event) {
        let isValid = true;
        let errorMessage = '';

        // Clear previous error messages
        if (errorAlert) {
            errorAlert.textContent = '';
            errorAlert.style.display = 'none';
        }

        // Validate Current Password
        if (currentPasswordInput.value.trim() === '') {
            isValid = false;
            errorMessage = 'Current password cannot be empty!';
        }

        // Validate New Username
        if (newUsernameInput.value.trim() === '') {
            isValid = false;
            errorMessage = 'New username cannot be empty!';
        } else if (!/^[a-zA-Z][a-zA-Z0-9 ]{2,19}$/.test(newUsernameInput.value.trim())) {
            isValid = false;
            errorMessage = 'Invalid new username! It must start with a letter, be 3-20 characters long, and contain only letters, numbers, and spaces.';
        }

        // Validate New Password
        if (newPasswordInput.value.trim() === '') {
            isValid = false;
            errorMessage = 'New password cannot be empty!';
        } else if (!/^[a-zA-Z0-9]{4,10}$/.test(newPasswordInput.value.trim())) {
            isValid = false;
            errorMessage = 'Invalid new password! It must be 4-10 characters long and contain only letters and numbers.';
        }

        // Display error message and prevent form submission if validation fails
        if (!isValid) {
            event.preventDefault();
            if (errorAlert) {
                errorAlert.textContent = errorMessage;
                errorAlert.style.display = 'block';
            }
        }
    });
});