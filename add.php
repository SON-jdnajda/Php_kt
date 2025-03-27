<?php
session_start();
include 'config.php';


if (!isset($_SESSION['user']) || $_SESSION['user']['role'] != 'admin') {
    die("<h2 style='color:red; text-align:center;'>Bạn không có quyền thêm nhân viên!</h2>");
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $Ma_NV = $_POST['Ma_NV'];
    $Ten_NV = $_POST['Ten_NV'];
    $Phai = $_POST['Phai'];
    $Noi_Sinh = $_POST['Noi_Sinh'];
    $Ma_Phong = $_POST['Ma_Phong'];
    $Luong = $_POST['Luong'];

    $checkStmt = $conn->prepare("SELECT Ma_NV FROM nhanvien WHERE Ma_NV = ?");
    $checkStmt->bind_param("s", $Ma_NV);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows > 0) {
        echo "<script>alert('Mã nhân viên đã tồn tại! Vui lòng nhập mã khác.');</script>";
    } else {
        
        $stmt = $conn->prepare("INSERT INTO nhanvien (Ma_NV, Ten_NV, Phai, Noi_Sinh, Ma_Phong, Luong) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssi", $Ma_NV, $Ten_NV, $Phai, $Noi_Sinh, $Ma_Phong, $Luong);
        if ($stmt->execute()) {
            echo "<script>alert('Thêm nhân viên thành công!'); window.location.href = 'admin.php';</script>";
        } else {
            echo "<script>alert('Lỗi khi thêm nhân viên!');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Nhân Viên</title>
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
        input, select {
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
    <h2>Thêm Nhân Viên</h2>
    <form method="POST">
        <label>Mã NV:</label> 
        <input type="text" name="Ma_NV" required>

        <label>Tên NV:</label> 
        <input type="text" name="Ten_NV" required>

        <label>Giới tính:</label> 
        <select name="Phai">
            <option value="NAM">Nam</option>
            <option value="NU">Nữ</option>
        </select>

        <label>Nơi Sinh:</label> 
        <input type="text" name="Noi_Sinh" required>

        <label>Phòng:</label> 
        <select name="Ma_Phong" required>
            <?php
            $result = $conn->query("SELECT Ma_Phong, Ten_Phong FROM phongban");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['Ma_Phong']}'>{$row['Ten_Phong']}</option>";
            }
            ?>
        </select>

        <label>Lương:</label> 
        <input type="number" name="Luong" required>

        <button type="submit">Thêm Nhân Viên</button>
    </form>

    <a href="admin.php" class="back-link">Quay lại</a>
</div>

</body>
</html>
