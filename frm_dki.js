document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.querySelector("input[name='password']");
    const strengthMessage = document.getElementById('strengthMessage');
    const segments = document.querySelectorAll('.strengthSegment');

    const resetForm = () => {
        document.querySelectorAll("input[name='name'], input[name='email'], input[name='username'], input[name='password'], input[name='password_confirm']").forEach(input => {
            input.value = '';
        });

        segments.forEach(segment => {
            segment.style.backgroundColor = 'transparent';
        });
        strengthMessage.textContent = '';
    };

    const checkPasswordStrength = () => {
        let strength = 0;
        const password = passwordInput.value;

        if (password.length >= 8) strength++;
        if (/[A-Z]/.test(password)) strength++;
        if (/[a-z]/.test(password)) strength++;
        if (/[0-9]/.test(password)) strength++;
        if (/[@$!%*?&#]/.test(password)) strength++;

        segments.forEach((segment, index) => {
            segment.style.backgroundColor = index < strength ? segmentColors(index) : 'transparent';
        });

        strengthMessage.textContent = strength ? '' : '';
    };

    const segmentColors = (index) => {
        switch (index) {
            case 0: return 'red'; 
            case 1: return 'orange'; 
            case 2: return 'yellow'; 
            case 3: return 'lightgreen'; 
            case 4: return 'green'; 
        }
    };

    passwordInput.addEventListener('input', checkPasswordStrength);

    document.getElementById('reset_value').addEventListener('click', resetForm);
});

function togglePasswordVisibility(inputName) {
    const inputField = document.querySelector(`input[name='${inputName}']`);
    const eyeIcon = document.getElementById(inputName === 'password' ? 'eyeIconPassword' : 'eyeIconConfirmPassword');

    if (inputField.type === 'password') {
        inputField.type = 'text';
        eyeIcon.src = 'eye-icon.png'; // Hình ảnh con mắt mở
    } else {
        inputField.type = 'password';
        eyeIcon.src = 'eye-off-icon.png'; // Hình ảnh con mắt đóng
    }
}




