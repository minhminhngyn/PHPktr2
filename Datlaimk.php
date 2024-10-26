<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt Lại Mật Khẩu</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #fff;
            color: #000;
        }
        h2 {
            margin-bottom: 20px;
        }
        .password-container {
            position: relative;
            width: 80%;
            max-width: 400px;
        }
        input[type="password"], input[type="text"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #000;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            font-size: 16px;
            color: #888;
        }
        button {
            padding: 10px 20px;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        #skipBtn {
            background-color: #888;
            color: #fff;
        }
        #saveBtn {
            background-color: #000;
            color: #fff;
        }
        .password-suggestion {
            color: #555;
            font-size: 14px;
            margin-top: -5px;
            margin-bottom: 15px;
            display: block;
        }
        .error-message {
            color: red;
            font-size: 14px;
            display: none;
            margin-top: -5px;
            margin-bottom: 15px;
        }
        .login-link {
            color: blue;
            text-decoration: underline;
            cursor: pointer;
            margin-left: 5px;
        }
    </style>
    <script>
        const oldPassword = "oldpassword123"; // Giả định mật khẩu cũ là "oldpassword123"

        function togglePasswordVisibility(inputId, toggleId) {
            const passwordInput = document.getElementById(inputId);
            const toggleIcon = document.getElementById(toggleId);

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.textContent = "🙈";
            } else {
                passwordInput.type = "password";
                toggleIcon.textContent = "👁️";
            }
        }

        function validatePasswords(event) {
            const newPassword = document.getElementById("newPassword").value;
            const confirmPassword = document.getElementById("confirmPassword").value;
            const errorMessage = document.getElementById("errorMessage");
            const loginLink = document.getElementById("loginLink");

            if (newPassword === oldPassword) {
                errorMessage.style.display = "block";
                errorMessage.textContent = "Mật khẩu mới trùng với mật khẩu cũ.";
                loginLink.style.display = "inline"; // Hiển thị link "Đăng nhập" nếu mật khẩu trùng
                event.preventDefault();
            } else if (newPassword !== confirmPassword) {
                errorMessage.style.display = "block";
                errorMessage.textContent = "Mật khẩu không khớp. Vui lòng thử lại.";
                loginLink.style.display = "none";
                event.preventDefault();
            } else {
                errorMessage.style.display = "none";
                loginLink.style.display = "none";
                alert("Đặt mật khẩu mới thành công!");
                window.location.href = "index.html"; // Chuyển hướng đến trang chủ
            }
        }
    </script>
</head>
<body>

<h2>Đặt Lại Mật Khẩu</h2>

<form onsubmit="validatePasswords(event)" style="display: flex; flex-direction: column; align-items: center;">
    <div class="password-container">
        <input type="password" id="newPassword" placeholder="Mật khẩu mới" required>
        <span id="toggleNewPassword" class="toggle-password" onclick="togglePasswordVisibility('newPassword', 'toggleNewPassword')">👁️</span>
    </div>
    <div id="passwordSuggestion" class="password-suggestion">Gợi ý: Tạo mật khẩu chứa ít nhất 8 ký tự, bao gồm chữ hoa, chữ thường, số và ký tự đặc biệt.</div>
    
    <div class="password-container">
        <input type="password" id="confirmPassword" placeholder="Nhập lại mật khẩu mới" required>
        <span id="toggleConfirmPassword" class="toggle-password" onclick="togglePasswordVisibility('confirmPassword', 'toggleConfirmPassword')">👁️</span>
    </div>
    
    <div id="errorMessage" class="error-message">Mật khẩu không khớp. Vui lòng thử lại.</div>
    <a id="loginLink" class="login-link" href="login.html" style="display: none;">Đăng nhập</a>

    <div style="display: flex;">
        <button type="button" id="skipBtn">Bỏ qua</button>
        <button type="submit" id="saveBtn">Lưu</button>
    </div>
</form>

</body>
</html>
