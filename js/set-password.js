// Get elements
const labels = document.querySelectorAll('.f0n8F');
const inputs = document.querySelectorAll('._2hvTZ');
const showBtns = document.querySelectorAll('.i24fI');
const showToggles = document.querySelectorAll('.show-toggle');
const submitBtn = document.querySelector('.submit-btn');

// Handle input placeholder animations
inputs.forEach((input, index) => {
    input.addEventListener('input', function () {
        if (input.value === '') {
            labels[index].classList.remove('FATdn');
            showBtns[index].style.display = 'none';
        } else {
            labels[index].classList.add('FATdn');
            showBtns[index].style.display = 'block';
        }

        // Validate passwords
        validatePasswords();
    });

    input.addEventListener('focus', function () {
        if (input.value !== '') {
            labels[index].classList.add('FATdn');
        }
    });
});

// Handle show/hide password
showToggles.forEach((toggle, index) => {
    toggle.addEventListener('click', function () {
        if (inputs[index].type === 'password') {
            inputs[index].type = 'text';
            toggle.textContent = 'Hide';
        } else {
            inputs[index].type = 'password';
            toggle.textContent = 'Show';
        }
    });
});

// Validate passwords match
function validatePasswords() {
    const newPassword = inputs[0].value;
    const confirmPassword = inputs[1].value;

    if (newPassword.length >= 6 && confirmPassword.length >= 6 && newPassword === confirmPassword) {
        submitBtn.disabled = false;
    } else {
        submitBtn.disabled = true;
    }
}

// Display error messages from URL
window.addEventListener('DOMContentLoaded', function () {
    const urlParams = new URLSearchParams(window.location.search);
    const error = urlParams.get('error');

    if (error === 'empty') {
        alert('Please fill in both password fields.');
    } else if (error === 'mismatch') {
        alert('Passwords do not match. Please try again.');
    }
});
