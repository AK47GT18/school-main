function validateForm(event) {
    event.preventDefault();
    let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    let passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
    let phoneRegex = /^\d{10}$/;
    let valid = true;

    const firstName = document.getElementById('FirstName');
    const firstNameError = document.getElementById('FirstNameError');
    if (!firstName.value.trim()) {
        firstNameError.textContent = 'First name is required';
        valid = false;
    } else {
        firstNameError.textContent = '';
    }

    const lastName = document.getElementById('LastName');
    const lastNameError = document.getElementById('LastNameError');
    if (!lastName.value.trim()) {
        lastNameError.textContent = 'Last name is required';
        valid = false;
    } else {
        lastNameError.textContent = '';
    }

    const email = document.getElementById('Email');
    const emailError = document.getElementById('EmailError');
    if (!email.value.trim()) {
        emailError.textContent = 'Email is required';
        valid = false;
    } else if (!emailRegex.test(email.value)) {
        emailError.textContent = 'Invalid email format example@mail.com';
        valid = false;
    } else {
        emailError.textContent = '';
    }

    const password = document.getElementById('Password');
    const passwordError = document.getElementById('PasswordError');
    if (!password.value.trim()) {
        passwordError.textContent = 'Password is required';
        valid = false;
    } else if (!passwordRegex.test(password.value)) {
        passwordError.textContent = 'Password must have 8-12 characters';
        valid = false;
    } else {
        passwordError.textContent = '';
    }

    const confirmPassword = document.getElementById('ConfirmPassword');
    const confirmPasswordError = document.getElementById('ConfirmPasswordError');
    if (!confirmPassword.value.trim()) {
        confirmPasswordError.textContent = 'Confirm password is required';
        valid = false;
    } else if (password.value !== confirmPassword.value) {
        confirmPasswordError.textContent = 'Passwords do not match';
        valid = false;
    } else {
        confirmPasswordError.textContent = '';
    }

    const phoneNumber = document.getElementById('PhoneNumber');
    const phoneError = document.getElementById('PhoneNumberError');
    if (!phoneNumber.value.trim()) {
        phoneError.textContent = 'Phone number is required';
        valid = false;
    } else if (!phoneRegex.test(phoneNumber.value)) {
        phoneError.textContent = 'Phone number must have 10 digits';
        valid = false;
    } else {
        phoneError.textContent = '';
    }

    const termsCheckbox = document.getElementById('check');
    const termsError = document.getElementById('checkError');
    if (!termsCheckbox.checked) {
        termsError.textContent = 'You must accept the terms and conditions';
        valid = false;
    } else {
        termsError.textContent = '';
    }

    if (valid) {
        alert('Sign-Up successfully');
     
        document.querySelector('form').submit();
    }

    return valid; 
}
function validate2Form(event) {
    event.preventDefault();
    let emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
    let passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;
    let valid = true;

    const email = document.getElementById('Email');
    const emailError = document.getElementById('EmailError');
    if (!email.value.trim()) {
        emailError.textContent = 'Email is required';
        valid = false;
    } else if (!emailRegex.test(email.value)) {
        emailError.textContent = 'Invalid email format example@mail.com';
        valid = false;
    } else {
        emailError.textContent = '';
    }

    const password = document.getElementById('Password');
    const passwordError = document.getElementById('PasswordError');
    if (!password.value.trim()) {
        passwordError.textContent = 'Password is required';
        valid = false;
    } else if (!passwordRegex.test(password.value)) {
        passwordError.textContent = 'Password must have 8-12 characters ';
        valid = false;
    } else {
        passwordError.textContent = '';
    }

    if (valid) {
        alert('Login successfully');
     
        document.querySelector('form').submit();
    }

    return valid; 
}
