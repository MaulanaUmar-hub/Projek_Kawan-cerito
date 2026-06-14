document.addEventListener('DOMContentLoaded', function () {
    function setupPasswordToggle(buttonId, inputId) {
        const toggleButton = document.getElementById(buttonId);
        const passwordInput = document.getElementById(inputId);
        
        if (toggleButton && passwordInput) {
            // Mengambil elemen icon di dalam button secara dinamis
            const icon = toggleButton.querySelector('i');

            toggleButton.addEventListener('click', function () {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                
                if (type === 'text') {
                    icon.classList.remove('bi-eye');
                    icon.classList.add('bi-eye-slash');
                } else {
                    icon.classList.remove('bi-eye-slash');
                    icon.classList.add('bi-eye');
                }
            });
        }
    }

    setupPasswordToggle('togglePassword', 'password');
    setupPasswordToggle('toggleConfirmPassword', 'password_confirmation');
});