<?php
session_start();
include 'connect.inp';

// if (!isset($_SESSION['PhanQuyen']) || $_SESSION['PhanQuyen'] != 'admin') {
//     echo "Bạn không có quyền truy cập trang này.";
//     exit();
// }

$search = isset($_POST['search']) ? trim($_POST['search']) : '';
$role_filter = isset($_POST['role_filter']) ? $_POST['role_filter'] : '';
$limit = 10;
$sql_total = "SELECT COUNT(*) as total FROM thongtintaikhoan WHERE 1=1";

if ($search) {
    $sql_total .= " AND TenDangNhap LIKE '%" . $con->real_escape_string($search) . "%'";
}
if ($role_filter) {
    $sql_total .= " AND PhanQuyen = '" . $con->real_escape_string($role_filter) . "'";
}

$result_total = $con->query($sql_total);
$total_accounts = $result_total->fetch_assoc()['total'];
$total_pages = ceil($total_accounts / $limit);
$current_page = max(1, min(isset($_GET['page']) ? (int)$_GET['page'] : 1, $total_pages));
$offset = ($current_page - 1) * $limit;

$sql = "SELECT * FROM thongtintaikhoan WHERE 1=1";
if ($search) {
    $sql .= " AND TenDangNhap LIKE '%" . $con->real_escape_string($search) . "%'";
}
if ($role_filter) {
    $sql .= " AND PhanQuyen = '" . $con->real_escape_string($role_filter) . "'";
}
$sql .= " LIMIT $limit OFFSET $offset";
$result = $con->query($sql);


if (isset($_POST['save_role'])) {
    $new_role = $_POST['new_role'];
    $MaTK = $_POST['MaTK'];
    if ($MaTK && $new_role) {
        $update_sql = "UPDATE thongtintaikhoan SET PhanQuyen = '" . $con->real_escape_string($new_role) . "' WHERE MaTK = '" . $con->real_escape_string($MaTK) . "'";
        $con->query($update_sql);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

if (isset($_POST['delete_user'])) {
    $MaTK = $_POST['MaTK'];
    if ($MaTK) {
        $delete_sql = "DELETE FROM thongtintaikhoan WHERE MaTK = '" . $con->real_escape_string($MaTK) . "'";
        $con->query($delete_sql);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản Lý Người Dùng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        h2 { text-align: center; margin-bottom: 20px; }
        form { margin-bottom: 20px; }
        table { width: 80%; border-collapse: collapse; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: center; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .pagination { margin-top: 20px; }
        .pagination a { margin: 0 5px; text-decoration: none; padding: 5px 10px; border: 1px solid #ddd; border-radius: 5px; color: #333; }
        .pagination a.active { background-color: gray; color: white; border: 1px solid gray; }
        .pagination a:hover:not(.active) { background-color: #ddd; }
        .button { background-color: gray; color: white; padding: 8px 16px; border: none; border-radius: 4px; cursor: pointer; margin: 5px; transition: background-color 0.3s; font-size: 15px; }
        .button:hover { background-color: black; }
    </style>
    <script>
        function checkRoleChange(selectElement, id) {
            const oldRole = selectElement.getAttribute('data-old-role');
            const newRole = selectElement.value;
            const saveButton = document.getElementById('save-' + id);
            const newRoleInput = document.getElementById('new-role-' + id);
            newRoleInput.value = newRole;
            saveButton.style.display = oldRole !== newRole ? 'inline-block' : 'none';
        }
    </script>
</head>
<body>

<h2>Quản Lý Người Dùng</h2>

<form method="POST" action="">
    <input type="text" name="search" placeholder="Tìm kiếm tài khoản" value="<?php echo htmlspecialchars($search); ?>">
    <select name="role_filter">
        <option value="">Tất cả vai trò</option>
        <option value="admin" <?php echo ($role_filter == 'admin') ? 'selected' : ''; ?>>Admin</option>
        <option value="user" <?php echo ($role_filter == 'user') ? 'selected' : ''; ?>>User</option>
    </select>
    <input type="submit" value="Tìm kiếm">
</form>

<?php if ($result && $result->num_rows > 0): ?>
    <table>
        <tr>
            <th>Tên Tài Khoản</th>
            <th>Phân Quyền</th>
            <th>Chức Năng</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['TenDangNhap']); ?></td>
                <td>
                    <select id="role-<?php echo $row['MaTK']; ?>" name="new_role" data-old-role="<?php echo htmlspecialchars($row['PhanQuyen']); ?>" onchange="checkRoleChange(this, '<?php echo $row['MaTK']; ?>')">
                        <option value="admin" <?php echo ($row['PhanQuyen'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="user" <?php echo ($row['PhanQuyen'] == 'user') ? 'selected' : ''; ?>>User</option>
                    </select>
                </td>
                <td>
                    <form method="POST" action="" style="display:inline;">
                        <input type="hidden" name="MaTK" value="<?php echo $row['MaTK']; ?>">
                        <input type="hidden" name="new_role" id="new-role-<?php echo $row['MaTK']; ?>" value="<?php echo htmlspecialchars($row['PhanQuyen']); ?>">
                        <input type="submit" name="save_role" value="Lưu" class="button" id="save-<?php echo $row['MaTK']; ?>" style="display: none;">
                        <a href="edit_user.php?MaTK=<?php echo $row['MaTK']; ?>" class="button">Chỉnh sửa</a>
                        <input type="submit" name="delete_user" value="Xóa" class="button" onclick="return confirm('Bạn có chắc chắn muốn xóa tài khoản này?');">
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?php echo $current_page - 1; ?>">« Trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>" <?php echo ($i == $current_page) ? 'class="active"' : ''; ?>><?php echo $i; ?></a>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?php echo $current_page + 1; ?>">Sau »</a>
        <?php endif; ?>
    </div>

<?php else: ?>
    <p>Không tìm thấy tài khoản nào.</p>
<?php endif; ?>

</body>
</html>
