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
        margin-top: 50px; /* Cách đầu trang 5cm */
        margin-bottom: 50px;
        text-align: center;
    }
    td {
            padding: 10px;
            vertical-align: middle;
            width: 50%; 
        }
    .password-container {
        position: relative;
        width: 80%;
        max-width: 400px;
    }
    input[type='password'] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
        min-width: 200px;
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
    #strengthBar {
        display: flex;
        width: 100%;
        height: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        margin: 15px auto 0; /* Khoảng cách với ô nhập mật khẩu */
    }

    .strengthSegment {
        flex: 1;
        height: 100%;
        transition: background-color 0.3s;
    }

    .strengthText {
        display: flex;
        justify-content: space-between;
        padding: 100 100px; /* Cân đối khoảng cách giữa "Mật khẩu yếu" và "Mật khẩu mạnh" */
        margin-top: 5px;
        font-size: 12px;
    }

    .weak { color: red; }
    .medium { color: orange; }
    .strong { color: green; }

    .toggle-password {
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
    }

    .toggle-password img {
        width: 25px;
        height: 25px;
    }

    input[type='submit'], 
    #reset_value {
        background-color: #111111;
        color: white;
        border: none;
        padding: 10px 35px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
        margin: 70px;
    }

    input[type='submit']:hover, 
    #reset_value:hover {
        background-color: #111111;
    }
</style>

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
    <tr>
        <td>Mật khẩu:</td>
        <td>
            <div style="position: relative;">
                <input type='password' name='password' id='password' class='password' required>
                <span class="toggle-password" onclick="togglePasswordVisibility('password')">
                    <img src="eye-off-icon.png" alt="Show Password" id="eyeIconPassword" style="cursor: pointer;">
                </span>
            </div>
        </td>
    </tr>
    <tr>
                    <td colspan="2">
                        <div id="strengthBar">
                            <div class="strengthSegment" id="segment1"></div>
                            <div class="strengthSegment" id="segment2"></div>
                            <div class="strengthSegment" id="segment3"></div>
                            <div class="strengthSegment" id="segment4"></div>
                            <div class="strengthSegment" id="segment5"></div>
                        </div>
                        <div class="strengthText">
                            <span class="weak">Mật khẩu yếu</span>
                            <span class="strong">Mật khẩu mạnh</span>
                        </div>
                        <p id='strengthMessage'></p>
                    </td>
                </tr>
                <tr>
                <td>Xác nhận mật khẩu:</td>
        <td>
            <div style="position: relative;">
                <input type='password' name='password_confirm' id='confirmPassword' class='password' required>
                <span class="toggle-password" onclick="togglePasswordVisibility('password_confirm')">
                    <img src="eye-off-icon.png" alt="Show Password" id="eyeIconConfirmPassword" style="cursor: pointer;">
                </span>
            </div>
        </td>
    </tr>
    <div style="text-align: center;"> 
        <button type='button' id='reset_value'>Bỏ qua</button>
        <input type='submit' value='Đặt lại mật khẩu'>
    </div>
</form>

<?php
include 'connect.php';

// Giả sử token được truyền qua URL
if (isset($_GET['token'])) {
    $token = $_GET['token'];
    
    // Tính toán thời gian hết hạn của token (1 giờ kể từ bây giờ)
    $expiry_time = date("Y-m-d H:i:s", strtotime("+1 hour"));
    
    // Query để lấy thông tin token và cập nhật thời gian hết hạn
    $stmt = $conn->prepare("SELECT MaTK, TgHethan FROM datlaimk WHERE Token = ? AND TrangThai = 'Active'");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $current_time = date("Y-m-d H:i:s");

        // Kiểm tra nếu token vẫn hợp lệ
        if ($current_time <= $row['TgHethan']) {
            // Cập nhật thời gian hết hạn trong bảng 'datlaimk'
            $update_stmt = $conn->prepare("UPDATE datlaimk SET TgHethan = ? WHERE Token = ?");
            $update_stmt->bind_param("ss", $expiry_time, $token);
            
            if ($update_stmt->execute()) {
                echo "<p>Token is valid. You can reset your password.</p>";
            } else {
                echo "<p>Lỗi khi cập nhật thời gian hết hạn.</p>";
            }
        } else {
            echo "<p>Token has expired. Please request a new password reset.</p>";
        }
    }
}
?>



    <script src="datlaimk.js"></script>
</body>
</html>
