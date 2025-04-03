<?php
session_start();
include 'db_connect.php';

// Kiểm tra nếu người dùng chưa đăng nhập thì chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Lấy mã sinh viên từ URL
$maSV = $_GET['MaSV'];

// Lấy thông tin chi tiết sinh viên từ cơ sở dữ liệu
$query = "SELECT * FROM SinhVien WHERE MaSV = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $maSV);
$stmt->execute();
$result = $stmt->get_result();
$sinhVien = $result->fetch_assoc();

// Kiểm tra nếu không tìm thấy sinh viên
if (!$sinhVien) {
    echo "Sinh viên không tồn tại!";
    exit;
}

?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi Tiết Sinh Viên</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Test1</a>
            <ul class="nav">
                <li class="nav-item"><a class="nav-link text-light" href="index.php">Sinh Viên</a></li>
                <li class="nav-item"><a class="nav-link text-light" href="#">Học Phần</a></li>
                <li class="nav-item"><a class="nav-link text-light" href="#">Đăng Ký</a></li>
                <li class="nav-item"><a class="nav-link text-light" href="login.php">Đăng Nhập</a></li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Chi Tiết Sinh Viên</h2>
        <div class="row">
            <div class="col-md-6">
                <p><strong>Mã Sinh Viên:</strong> <?= $sinhVien['MaSV'] ?></p>
                <p><strong>Họ Tên:</strong> <?= $sinhVien['HoTen'] ?></p>
                <p><strong>Giới Tính:</strong> <?= $sinhVien['GioiTinh'] ?></p>
                <p><strong>Ngày Sinh:</strong> <?= date('d/m/Y', strtotime($sinhVien['NgaySinh'])) ?></p>
                <p><strong>Ngành:</strong> <?= $sinhVien['MaNganh'] ?></p>
            </div>
            <div class="col-md-6">
                <p><strong>Ảnh:</strong></p>
                <img src="<?= $sinhVien['Hinh'] ?>" class="img-fluid" alt="Sinh Viên">
            </div>
        </div>
        <a href="index.php" class="btn btn-primary">Quay lại danh sách sinh viên</a>
    </div>
</body>
</html>
