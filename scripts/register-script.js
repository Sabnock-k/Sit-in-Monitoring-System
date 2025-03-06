window.addEventListener('DOMContentLoaded', () => {
    const passwordField = document.getElementById('password');
    const confirmPasswordField = document.getElementById('confirm_password');
    const passwordIndicator = document.getElementById('passwordIndicator');
    const confirmPasswordError = document.getElementById('confirmPasswordError');

    VANTA.GLOBE({
    el: "#globe",
    mouseControls: true,
    touchControls: true,
    gyroControls: false,
    minHeight: 200.00,
    minWidth: 200.00,
    scale: 1.00,
    scaleMobile: 1.00,
    color: 0xD29C00,
    backgroundColor: 0x0e2f60
    })

    function checkPasswordMatch() {
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;
        
        if (confirmPassword === '') {
            // When confirm field is empty
            passwordIndicator.style.backgroundColor = '#e0e0e0';
            confirmPasswordError.textContent = '';
            confirmPasswordError.classList.remove('shake');
            return;
        }
        
        if (password === confirmPassword) {
            // When passwords match
            passwordIndicator.style.backgroundColor = '#4CAF50';
            passwordIndicator.classList.add('animate__animated', 'animate__pulse');
            confirmPasswordError.textContent = '';
            confirmPasswordField.style.borderColor = '#4CAF50';
            setTimeout(() => {
                passwordIndicator.classList.remove('animate__animated', 'animate__pulse');
            }, 1000);
        } else {
            // When passwords don't match
            passwordIndicator.style.backgroundColor = '#f44336';
            confirmPasswordError.textContent = 'Passwords do not match';
            confirmPasswordError.classList.add('animate__animated', 'animate__fadeIn', 'shake');
            confirmPasswordField.style.borderColor = '#f44336';
            setTimeout(() => {
                confirmPasswordError.classList.remove('shake');
            }, 500);
        }
    }
    
    // Event listeners for checking password match
    confirmPasswordField.addEventListener('input', checkPasswordMatch);
    passwordField.addEventListener('input', function() {
        if (confirmPasswordField.value !== '') {
            checkPasswordMatch();
        }
    });
    
    // Add animation to form submission
    const form = document.getElementById('registerForm');
    form.addEventListener('submit', function(event) {
        const password = passwordField.value;
        const confirmPassword = confirmPasswordField.value;
        
        if (password !== confirmPassword) {
            event.preventDefault();
            confirmPasswordError.textContent = 'Passwords do not match';
            confirmPasswordError.classList.add('shake');
            confirmPasswordField.classList.add('w3-border-red');
            setTimeout(() => {
                confirmPasswordError.classList.remove('shake');
            }, 500);
        }
    });
});


