<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    die("<h2 style='color:red; text-align:center;'>Bạn không có quyền chỉnh sửa nhân viên!</h2>");
}

$ma_nv = $_GET['Ma_NV'];

// Lấy thông tin nhân viên
$stmt = $conn->prepare("SELECT * FROM nhanvien WHERE Ma_NV = ?");
$stmt->bind_param("s", $ma_nv);
$stmt->execute();
$result = $stmt->get_result();
$emp = $result->fetch_assoc();

if (!$emp) {
    die("<h2 style='color:red; text-align:center;'>Không tìm thấy nhân viên!</h2>");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $conn->prepare("UPDATE nhanvien SET Ten_NV = ?, Noi_Sinh = ?, Luong = ? WHERE Ma_NV = ?");
    $stmt->bind_param("ssis", $_POST['Ten_NV'], $_POST['Noi_Sinh'], $_POST['Luong'], $ma_nv);
    if ($stmt->execute()) {
        echo "<script>alert('Cập nhật thành công!'); window.location.href = 'admin.php';</script>";
    } else {
        echo "<script>alert('Lỗi khi cập nhật!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chỉnh Sửa Nhân Viên</title>
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
            color: #333;
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
            display: block;
            margin-top: 10px;
            text-align: left;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            width: 100%;
            padding: 10px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 15px;
            font-size: 16px;
        }
        button:hover {
            background: #218838;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
            color: white;
            background: #007BFF;
            padding: 8px 15px;
            border-radius: 5px;
        }
        .back-link:hover {
            background: #0056b3;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Chỉnh Sửa Nhân Viên</h2>
    <form method="POST">
        <label>Tên NV:</label>
        <input type="text" name="Ten_NV" value="<?= htmlspecialchars($emp['Ten_NV']) ?>" required>

        <label>Nơi Sinh:</label>
        <input type="text" name="Noi_Sinh" value="<?= htmlspecialchars($emp['Noi_Sinh']) ?>" required>

        <label>Lương:</label>
        <input type="number" name="Luong" value="<?= htmlspecialchars($emp['Luong']) ?>" required>

        <button type="submit">Cập nhật</button>
    </form>

    <a href="admin.php" class="back-link">Quay lại</a>
</div>

</body>
</html>
