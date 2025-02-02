document.addEventListener('DOMContentLoaded', function () {
    
    const manageCustomersButton = document.querySelector('button[onclick*="mCustomer.php"]');
    if (manageCustomersButton) {
        manageCustomersButton.addEventListener('click', function (event) {
            const confirmAction = confirm('Are you sure you want to manage customers?');
            if (!confirmAction) {
                event.preventDefault(); 
            }
        });
    }

    
    const welcomeMessage = document.createElement('div');
    welcomeMessage.textContent = 'Welcome back!';
    welcomeMessage.style.position = 'fixed';
    welcomeMessage.style.top = '20px';
    welcomeMessage.style.right = '20px';
    welcomeMessage.style.backgroundColor = '#4CAF50';
    welcomeMessage.style.color = 'white';
    welcomeMessage.style.padding = '10px';
    welcomeMessage.style.borderRadius = '5px';
    welcomeMessage.style.boxShadow = '0 2px 5px rgba(0, 0, 0, 0.2)';
    welcomeMessage.style.zIndex = '1000';
    document.body.appendChild(welcomeMessage);

    
    setTimeout(() => {
        welcomeMessage.remove();
    }, 3000);
});