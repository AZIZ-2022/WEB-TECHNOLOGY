document.addEventListener('DOMContentLoaded', function () {
    
    function updateCounts() {
        fetch('get_counts.php')
            .then(response => response.json())
            .then(data => {
                if (data.employee_count !== undefined) {
                    document.getElementById('employee-count').textContent = data.employee_count;
                }
                if (data.customer_count !== undefined) {
                    document.getElementById('customer-count').textContent = data.customer_count;
                }
            })
            .catch(error => console.error('Error fetching counts:', error));
    }

    
    if (!window.countUpdateInterval) {
        window.countUpdateInterval = setInterval(updateCounts, 60000);
    }


    document.querySelectorAll('button[onclick*="mCustomer.php"]').forEach(button => {
        button.addEventListener('click', function (event) {
            if (!confirm('Are you sure you want to manage customers?')) {
                event.preventDefault();
            }
        });
    });

  
    const welcomeMessage = document.createElement('div');
    welcomeMessage.textContent = 'Welcome back!';
   // welcomeMessage.classList.add("random")
    // Object.assign(welcomeMessage.style, {
    //     position: 'fixed',
    //     top: '20px',
    //     right: '20px',
    //     backgroundColor: '#4CAF50',
    //     color: 'white',
    //     padding: '10px',
    //     borderRadius: '5px',
    //     boxShadow: '0 2px 5px rgba(0, 0, 0, 0.2)',
    //     zIndex: '1000',
    //     opacity: '1',
    //     transition: 'opacity 1s ease-out'
    // });

    document.body.appendChild(welcomeMessage);

    setTimeout(() => {
        welcomeMessage.style.opacity = '0';
        setTimeout(() => welcomeMessage.remove(), 1000);
    }, 3000);
});
