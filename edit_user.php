<?php
session_start();
include 'connect.inp';

if (!isset($_GET['MaTK'])) {
    echo "<script>alert('Mã tài khoản không hợp lệ.'); window.history.back();</script>";
    exit();
}

$maTK = $_GET['MaTK'];

$sql = "SELECT a.MaTK, a.TenDangNhap, a.PhanQuyen, b.TenKH, b.NgaySinh, b.DiaChi, b.Email, b.SDT 
        FROM thongtintaikhoan a 
        JOIN thongtincanhan b ON a.MaKH = b.MaKH 
        WHERE a.MaTK = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param('s', $maTK);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    echo "<script>alert('Không tìm thấy tài khoản.'); window.history.back();</script>";
    exit();
}

$row = $result->fetch_assoc();
$alertMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $TenDangNhap = $_POST['TenDangNhap'];
    $PhanQuyen = $_POST['PhanQuyen'];
    $TenKH = $_POST['TenKH'];
    $ngaySinh = $_POST['NgaySinh'];
    $DiaChi = $_POST['DiaChi'];
    $email = $_POST['Email'];
    $sdt = $_POST['SDT'];
    $matKhau = $_POST['MatKhau'];

    if (!empty($matKhau)) {
        $matKhau = password_hash($matKhau, PASSWORD_BCRYPT);
        $updateAccountSql = "UPDATE thongtintaikhoan SET TenDangNhap=?, Matkhau=?, PhanQuyen=?, NgaySua=NOW() WHERE MaTK=?";
        $updateAccountStmt = $con->prepare($updateAccountSql);
        $updateAccountStmt->bind_param('ssss', $TenDangNhap, $matKhau, $PhanQuyen, $maTK);
    } else {
        $updateAccountSql = "UPDATE thongtintaikhoan SET TenDangNhap=?, PhanQuyen=?, NgaySua=NOW() WHERE MaTK=?";
        $updateAccountStmt = $con->prepare($updateAccountSql);
        $updateAccountStmt->bind_param('sss', $TenDangNhap, $PhanQuyen, $maTK);
    }

    $updatePersonalSql = "UPDATE thongtincanhan SET TenKH=?, NgaySinh=?, DiaChi=?, Email=?, SDT=? WHERE MaKH=(SELECT MaKH FROM thongtintaikhoan WHERE MaTK=?)";
    $updatePersonalStmt = $con->prepare($updatePersonalSql);
    $updatePersonalStmt->bind_param('ssssss', $TenKH, $ngaySinh, $DiaChi, $email, $sdt, $maTK);
    
    if ($updateAccountStmt->execute() && $updatePersonalStmt->execute()) {
        $alertMessage = "Cập nhật thông tin thành công!";
    } else {
        $alertMessage = "Lỗi: " . $con->error;
    }
}

if ($alertMessage) {
    echo "<script>alert('$alertMessage'); window.history.back();</script>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Người Dùng</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 20px; }
        h2 { color: #333; text-align: center; }
        form { background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); max-width: 400px; margin: auto; }
        label { display: block; margin: 10px 0 5px; }
        input[type="text"], input[type="email"], input[type="date"], input[type="password"], select { width: 100%; padding: 10px; margin-bottom: 15px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        input[type="submit"] { background-color: black; color: white; border: none; padding: 10px; border-radius: 4px; cursor: pointer; font-size: 16px; }
        input[type="submit"]:hover { background-color: gray; }
        .password-container { position: relative; }
        .password-container img { position: absolute; right: 10px; top: 40%; transform: translateY(-50%); cursor: pointer; width: 25px; height: 25px; }
    </style>
</head>
<body>

<h2>Chỉnh Sửa Thông Tin Người Dùng</h2>

<form method="POST">
    <label for="TenDangNhap">Tên Tài Khoản:</label>
    <input type="text" name="TenDangNhap" value="<?php echo htmlspecialchars($row['TenDangNhap']); ?>" required>

    <label for="PhanQuyen">Vai Trò:</label>
    <select name="PhanQuyen">
        <option value="admin" <?php echo ($row['PhanQuyen'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
        <option value="user" <?php echo ($row['PhanQuyen'] == 'user') ? 'selected' : ''; ?>>User</option>
    </select>

    <label for="TenKH">Họ Tên:</label>
    <input type="text" name="TenKH" value="<?php echo htmlspecialchars($row['TenKH']); ?>" required>

    <label for="NgaySinh">Ngày Sinh:</label>
    <input type="date" name="NgaySinh" value="<?php echo htmlspecialchars($row['NgaySinh']); ?>" required>

    <label for="DiaChi">Địa Chỉ:</label>
    <input type="text" name="DiaChi" value="<?php echo htmlspecialchars($row['DiaChi']); ?>" required>

    <label for="Email">Email:</label>
    <input type="email" name="Email" value="<?php echo htmlspecialchars($row['Email']); ?>" required>

    <label for="SDT">Số Điện Thoại:</label>
    <input type="text" name="SDT" value="<?php echo htmlspecialchars($row['SDT']); ?>" required>

    <label for="MatKhau">Mật Khẩu Mới:</label>
    <div class="password-container">
        <input type="password" id="MatKhau" name="MatKhau" placeholder="Nhập mật khẩu mới (nếu có)" style="padding-right: 40px;">
        <img src="eye-off-icon.png" id="togglePassword" alt="Toggle Password Visibility">
    </div>

    <input type="submit" value="Cập Nhật">
</form>

<script>
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('MatKhau');

    togglePassword.addEventListener('click', function () {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.src = type === 'password' ? 'eye-off-icon.png' : 'eye-icon.png';
    });
</script>

</body>
</html>
