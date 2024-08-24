function togglePassword(id) {
    const passwordField = document.getElementById(id);
    const toggleButton = passwordField.nextElementSibling;
    const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordField.setAttribute('type', type);
    toggleButton.textContent = type === 'password' ? 'SHOW' : 'HIDE';
}

function validatePassword() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    const resetButton = document.getElementById('reset-button');

    const lowercasePattern = /[a-z]/;
    const uppercasePattern = /[A-Z]/;
    const numberPattern = /[0-9]/;
    const specialPattern = /[^A-Za-z0-9]/;
    const minLength = 8;

    const hasLowercase = lowercasePattern.test(password);
    const hasUppercase = uppercasePattern.test(password);
    const hasNumber = numberPattern.test(password);
    const hasSpecial = specialPattern.test(password);
    const hasMinLength = password.length >= minLength;
    const passwordsMatch = password === confirmPassword;

    document.getElementById('lowercase').className = hasLowercase ? 'text-emerald-500' : 'text-gray-500';
    document.getElementById('uppercase').className = hasUppercase ? 'text-emerald-500' : 'text-gray-500';
    document.getElementById('number').className = hasNumber ? 'text-emerald-500' : 'text-gray-500';
    document.getElementById('special').className = hasSpecial ? 'text-emerald-500' : 'text-gray-500';
    document.getElementById('length').className = hasMinLength ? 'text-emerald-500' : 'text-gray-500';

    const isValid = hasLowercase && hasUppercase && hasNumber && hasSpecial && hasMinLength && passwordsMatch;

    document.getElementById('password').classList.toggle('border-emerald-500', isValid);
    document.getElementById('password_confirmation').classList.toggle('border-emerald-500', passwordsMatch);

    resetButton.disabled = !isValid;
}