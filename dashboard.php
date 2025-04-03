<?php
session_start();
include 'db_connect.php';

// Kiểm tra nếu người dùng chưa đăng nhập thì chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Lấy thông tin sinh viên từ cơ sở dữ liệu
$maSV = $_SESSION['user'];
$stmt = $conn->prepare("SELECT * FROM SinhVien WHERE MaSV = ?");
$stmt->bind_param("s", $maSV);  // 's' cho kiểu dữ liệu string
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Kiểm tra nếu không tìm thấy người dùng
if (!$user) {
    header('Location: login.php');
    exit;
}

// Lấy tên của sinh viên để hiển thị
$hoTen = $user['HoTen'];
$role = $user['Role'];  // Kiểm tra quyền
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Test1</a>
        <ul class="nav">
            <li class="nav-item"><a class="nav-link text-light" href="#">Sinh Viên</a></li>
            <li class="nav-item"><a class="nav-link text-light" href="#">Học Phần</a></li>
            <li class="nav-item"><a class="nav-link text-light" href="register.php">Đăng ký học phần</a></li>
            <li class="nav-item"><a class="nav-link text-light" href="login.php">Đăng Nhập</a></li>
            <div class="d-flex">
                <a class="btn btn-danger" href="logout.php">Đăng xuất</a>
            </div>
        </ul>
    </div>
</nav>

<div class="container mt-4">
    <h2>Xin chào, <?php echo $hoTen; ?></h2>
    <?php if ($role === 'admin'): ?>
        <div class="alert alert-info">
            <h4>Chào mừng, Admin!</h4>
            <p>Bạn có quyền truy cập quản trị toàn bộ hệ thống.</p>
            <a href="admin_dashboard.php" class="btn btn-primary">Quản lý hệ thống</a>
        </div>
    <?php else: ?>
        <div class="alert alert-success">
            <h4>Chào mừng, Sinh viên!</h4>
            <p>Bạn có thể xem thông tin đăng ký học phần của mình.</p>
            <a href="cart.php" class="btn btn-primary">Xem học phần đã đăng ký của tôi</a>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
