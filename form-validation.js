document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    loginForm.addEventListener('submit', function(event) {
        let valid = true;
        
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const emailError = document.getElementById('emailError');
        const passwordError = document.getElementById('passwordError');

        // Clear previous errors
        emailError.style.display = 'none';
        passwordError.style.display = 'none';
        emailInput.classList.remove('invalid');
        passwordInput.classList.remove('invalid');

        // Validate email
        if (!emailInput.value) {
            emailError.textContent = 'Email is required.';
            emailError.style.display = 'block';
            emailInput.classList.add('invalid');
            valid = false;
        } else if (!emailInput.value.includes('@')) {
            emailError.textContent = 'You must include "@" in the email address.';
            emailError.style.display = 'block';
            emailInput.classList.add('invalid');
            valid = false;
        }

        // Validate password
        if (!passwordInput.value) {
            passwordError.textContent = 'Password is required.';
            passwordError.style.display = 'block';
            passwordInput.classList.add('invalid');
            valid = false;
        }

        // If not valid, prevent form from submitting
        if (!valid) {
            event.preventDefault();
        }
    });
});
