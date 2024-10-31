<?php
session_start();
include 'connect.inp';

// Xử lý các tham số tìm kiếm và phân trang
$search = isset($_POST['search']) ? trim($_POST['search']) : '';
$role_filter = isset($_POST['role_filter']) ? $_POST['role_filter'] : '';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Nếu người dùng nhấn "Lưu" để cập nhật vai trò
if (isset($_POST['save_role'])) {
    $new_role = $_POST['new_role'];
    $MaTK = $_POST['MaTK'];
    if ($MaTK && $new_role) {
        $update_sql = "UPDATE thongtintaikhoan SET PhanQuyen = '" . $con->real_escape_string($new_role) . "' WHERE MaTK = '" . $con->real_escape_string($MaTK) . "'";
        $con->query($update_sql);
    }
}

// Nếu người dùng nhấn "Xóa" để xóa tài khoản
if (isset($_POST['delete_user'])) {
    $MaTK = $_POST['MaTK'];
    if ($MaTK) {
        $delete_sql = "DELETE FROM thongtintaikhoan WHERE MaTK = '" . $con->real_escape_string($MaTK) . "'";
        $con->query($delete_sql);
    }
}

// Chuyển hướng đến qluserview.php với các tham số tìm kiếm và trang
header("Location: qluserview.php?search=" . urlencode($search) . "&role_filter=" . urlencode($role_filter) . "&page=" . $page);
exit();
?>
