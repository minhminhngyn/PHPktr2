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
        function validatePasswords(event) {
            const newPassword = document.getElementById("newPassword").value;
            const confirmPassword = document.getElementById("confirmPassword").value;
            const errorMessage = document.getElementById("errorMessage");
            
            if (newPassword !== confirmPassword) {
                errorMessage.style.display = "block";
                event.preventDefault();
            } else {
                errorMessage.style.display = "none";
                document.getElementById("resetForm").submit();
            }
        }
    </script>
</head>
<body>
    <h2>Đặt Lại Mật Khẩu</h2>
    <form id="resetForm" method="post" onsubmit="validatePasswords(event)">
        <input type="password" id="newPassword" name="new_password" placeholder="Mật khẩu mới" required>
        <input type="password" id="confirmPassword" placeholder="Nhập lại mật khẩu mới" required>
        <div id="errorMessage" style="display: none; color: red;">Mật khẩu không khớp!</div>
        <button type="submit">Lưu</button>
    </form>

    <?php
    include 'connect.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $newPassword = $_POST['new_password'];
        $token = $_GET['token'];

        $stmt = $conn->prepare("SELECT MaTK FROM datlaimk WHERE Token = ? AND Trangthai = 0 AND TgHetHan > NOW()");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $MaTK = $row['MaTK'];
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

            $updateStmt = $conn->prepare("UPDATE thongtintaikhoan SET MatKhau = ? WHERE MaTK = ?");
            $updateStmt->bind_param("si", $hashedPassword, $MaTK);

            if ($updateStmt->execute()) {
                $conn->query("UPDATE datlaimk SET Trangthai = 1 WHERE Token = '$token'");
                echo "<script>
                        alert('Mật khẩu đã thay đổi thành công!');
                        window.location.href = 'Dangnhap.php?username=" . urlencode($MaTK) . "&password=" . urlencode($newPassword) . "';
                      </script>";
            }
        } else {
            echo "<p>Token không hợp lệ hoặc đã hết hạn!</p>";
        }
    }
    ?>
</body>
</html>