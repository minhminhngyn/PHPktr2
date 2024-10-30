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
        <button type='button' id='reset_value'>Làm lại</button>
        <input type='submit' value='Đặt lại mật khẩu'>
    </div>
</form>

    <?php
    include 'connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newPassword = isset($_POST['new_password']) ? $_POST['new_password'] : null;
        $token = isset($_GET['token']) ? $_GET['token'] : null;

        if ($newPassword && $token) {
            $stmt = $conn->prepare("SELECT MaTK FROM datlaimk WHERE Token = ? AND Trangthai = 0 AND TgHetHan > NOW()");
            $stmt->bind_param("s", $token);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $MaTK = $row['MaTK'];
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $NgaySua = date("Y-m-d H:i:s");
                $NguoiSua = "Admin"; // hoặc lấy thông tin từ session nếu có

                $updateStmt = $conn->prepare("UPDATE thongtintaikhoan SET MatKhau = ?, NgaySua = ?, NguoiSua = ?, TrangThaiHoatDong = 1 WHERE MaTK = ?");
                $updateStmt->bind_param("sssi", $hashedPassword, $NgaySua, $NguoiSua, $MaTK);
                $updateStmt = $conn->prepare("UPDATE thongtintaikhoan SET MatKhau = ? WHERE MaTK = ?");
                $updateStmt->bind_param("si", $hashedPassword, $MaTK);

                if ($updateStmt->execute()) {
                    $conn->query("UPDATE datlaimk SET Trangthai = 1 WHERE Token = '$token'");
                    echo "<script>
                            alert('Đặt lại mật khẩu thành công!');
                            setTimeout(function() {
                                window.location.href = 'dangnhap.php';
                            }, 1500); // Thời gian chờ là 1500ms (1.5 giây)
                          </script>";
                }
            } else {
                echo "<p>Token không hợp lệ hoặc đã hết hạn!</p>";
            }
        }
    }
    ?>
    <script src="datlaimk.js"></script>
</body>
</html>
