<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    die("<h2 style='color:red; text-align:center;'>Bạn không có quyền xóa nhân viên!</h2>");
}

$ma_nv = isset($_GET['Ma_NV']) ? $_GET['Ma_NV'] : null;
$nhanvien = null;

if ($ma_nv) {
    // Kiểm tra xem nhân viên có tồn tại không
    $stmt = $conn->prepare("SELECT * FROM nhanvien WHERE Ma_NV = ?");
    $stmt->bind_param("s", $ma_nv);
    $stmt->execute();
    $result = $stmt->get_result();
    $nhanvien = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_delete'])) {
    $stmt = $conn->prepare("DELETE FROM nhanvien WHERE Ma_NV = ?");
    $stmt->bind_param("s", $ma_nv);
    if ($stmt->execute()) {
        echo "<script>alert('Xóa nhân viên thành công!'); window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi xóa nhân viên!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xóa Nhân Viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 400px;
            margin: auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            color: #d9534f;
        }
        p {
            color: #333;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            margin: 10px;
            cursor: pointer;
        }
        .btn-danger {
            background: #d9534f;
            color: white;
            border: none;
        }
        .btn-danger:hover {
            background: #c9302c;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
            border: none;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
    </style>
</head>
<body>

<div class="container">
    <?php if ($nhanvien): ?>
        <h2>Xác nhận xóa nhân viên</h2>
        <p>Bạn có chắc chắn muốn xóa nhân viên <strong><?= htmlspecialchars($nhanvien['Ten_NV']) ?></strong>?</p>
        
        <form method="POST">
            <button type="submit" name="confirm_delete" class="btn btn-danger">Xóa</button>
            <a href="admin.php" class="btn btn-secondary">Hủy</a>
        </form>
    <?php else: ?>
        <h2>Nhân viên không tồn tại!</h2>
        <a href="admin.php" class="btn btn-secondary">Quay lại</a>
    <?php endif; ?>
</div>

</body>
</html>
