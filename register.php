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

// Lấy danh sách học phần từ cơ sở dữ liệu
$query = "SELECT * FROM HocPhan";
$result_hp = mysqli_query($conn, $query);

// Xử lý đăng ký học phần
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $maHP = $_POST['MaHP'];

    // Tạo đăng ký mới trong bảng DangKy
    $ngayDK = date('Y-m-d');  // Ngày hiện tại
    $stmt = $conn->prepare("INSERT INTO DangKy (NgayDK, MaSV) VALUES (?, ?)");
    $stmt->bind_param("ss", $ngayDK, $maSV);
    if ($stmt->execute()) {
        // Lấy mã đăng ký vừa tạo
        $maDK = $stmt->insert_id;

        // Tạo chi tiết đăng ký mới trong bảng ChiTietDangKy
        $stmt2 = $conn->prepare("INSERT INTO ChiTietDangKy (MaDK, MaHP) VALUES (?, ?)");
        $stmt2->bind_param("is", $maDK, $maHP);
        $stmt2->execute();

        $message = "Đã đăng ký học phần thành công!";
    } else {
        $message = "Có lỗi xảy ra khi tạo đăng ký.";
    }
}

// Lấy các học phần đã đăng ký từ giỏ hàng
if (isset($_SESSION['cart'])) {
    $cart = $_SESSION['cart'];
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký Học Phần</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Đăng Ký Học Phần</h2>

    <!-- Hiển thị thông báo nếu có -->
    <?php if (isset($message)): ?>
        <div class="alert alert-info"><?= $message ?></div>
    <?php endif; ?>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã Học Phần</th>
                <th>Tên Học Phần</th>
                <th>Số Tín Chỉ</th>
                <th>Đăng Ký</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($hp = mysqli_fetch_assoc($result_hp)): ?>
                <tr>
                    <td><?= $hp['MaHP'] ?></td>
                    <td><?= $hp['TenHP'] ?></td>
                    <td><?= $hp['SoTinChi'] ?></td>
                    <td>
                        <form method="POST">
                            <input type="hidden" name="MaHP" value="<?= $hp['MaHP'] ?>">
                            <button type="submit" name="register" class="btn btn-primary btn-sm">Đăng Ký</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <a href="cart.php" class="btn btn-success">Xem Giỏ Học Phần</a>
</div>
</body>
</html>
