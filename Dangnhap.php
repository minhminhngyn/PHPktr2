<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập</title>
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
            color: #000;
        }
        input[type="text"], input[type="password"], button {
            width: 90%;
            max-width: 500px;
            padding: 13px;
            margin: 10px 0;
            border-radius: 5px;
            border: 1px solid #000;
            font-size: 16px;
        }
        button {
            width: 80%;
            max-width: 250px;
            padding: 13px;
            background-color: #000;
            color: #fff;
            cursor: pointer;
            font-size: 16px;
            border: none;
            border-radius: 5px;
        }
        button:hover {
            background-color: #333;
        }
    </style>
</head>
<body>

<h2>Đăng Nhập</h2>
<form action="login_process.php" method="post">
        <input type="text" name="username" placeholder="Tên đăng nhập" value="<?php echo isset($_GET['username']) ? htmlspecialchars($_GET['username']) : ''; ?>">
        <input type="password" name="password" placeholder="Mật khẩu" value="<?php echo isset($_GET['password']) ? htmlspecialchars($_GET['password']) : ''; ?>">
        <button type="submit">Đăng Nhập</button>
    </form>

</body>
</html>
    <script>
        function validatePasswords(event) {
            const newPassword = document.getElementById("newPassword").value;
            const confirmPassword = document.getElementById("confirmPassword").value;

            if (newPassword !== confirmPassword) {
                alert("Mật khẩu không khớp!");
                event.preventDefault();
            } else {
                alert("Đặt lại mật khẩu thành công!");
            }
        }
    </script>
</head>
<body>
    <h2>Đặt Lại Mật Khẩu</h2>
    <form id="resetForm" method="post" onsubmit="validatePasswords(event)">
        <table>
            <tr>
                <td>Mật khẩu:</td>
                <td>
                    <div style="position: relative;">
                        <input type='password' id='newPassword' name='new_password' required>
                        <span class="toggle-password" onclick="togglePasswordVisibility('newPassword')">
                            <img src="eye-off-icon.png" alt="Show Password" id="eyeIconPassword" style="cursor: pointer;">
                        </span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Xác nhận mật khẩu:</td>
                <td>
                    <div style="position: relative;">
                        <input type='password' id='confirmPassword' name='password_confirm' required>
                        <span class="toggle-password" onclick="togglePasswordVisibility('confirmPassword')">
                            <img src="eye-off-icon.png" alt="Show Password" id="eyeIconConfirmPassword" style="cursor: pointer;">
                        </span>
                    </div>
                </td>
            </tr>
        </table>
        <div style="text-align: center;"> 
            <button type='button' id='reset_value'>Làm lại</button>
            <input type='submit' value='Đặt lại mật khẩu'>
        </div>
    </form>
