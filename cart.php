<?php
session_start();
include 'db_connect.php';

// Kiểm tra nếu người dùng chưa đăng nhập thì chuyển hướng đến trang đăng nhập
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

// Lấy mã sinh viên từ session
$maSV = $_SESSION['user'];

// Lấy thông tin học phần đã đăng ký từ bảng DangKy và ChiTietDangKy
$query = "
    SELECT hp.MaHP, hp.TenHP, hp.SoTinChi 
    FROM HocPhan hp
    JOIN ChiTietDangKy ctdk ON hp.MaHP = ctdk.MaHP
    JOIN DangKy dk ON ctdk.MaDK = dk.MaDK
    WHERE dk.MaSV = ?
";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $maSV);  // 's' cho kiểu dữ liệu string
$stmt->execute();
$result = $stmt->get_result();

// Lấy danh sách học phần đã đăng ký
$hocPhanList = [];
$totalCredits = 0; // Biến để tính tổng số tín chỉ

while ($row = $result->fetch_assoc()) {
    $hocPhanList[] = $row;
    $totalCredits += $row['SoTinChi'];  // Cộng dồn số tín chỉ
}

$totalCourses = count($hocPhanList);  // Đếm số lượng học phần
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ Học Phần</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
    <h2>Giỏ Học Phần</h2>

    <!-- Hiển thị thông báo nếu có -->
    <?php if (empty($hocPhanList)): ?>
        <p>Không có học phần nào trong giỏ!</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Mã Học Phần</th>
                    <th>Tên Học Phần</th>
                    <th>Số Tín Chỉ</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hocPhanList as $hp): ?>
                    <tr>
                        <td><?= $hp['MaHP'] ?></td>
                        <td><?= $hp['TenHP'] ?></td>
                        <td><?= $hp['SoTinChi'] ?></td>
                        <td>
                            <!-- Thêm hành động xóa học phần khỏi giỏ -->
                            <a href="remove.php?MaHP=<?= $hp['MaHP'] ?>" class="btn btn-danger btn-sm">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Hiển thị tổng số học phần và tổng số tín chỉ -->
        <div class="mt-3">
            <p><strong>Tổng số học phần: </strong><?= $totalCourses ?></p>
            <p><strong>Tổng số tín chỉ: </strong><?= $totalCredits ?></p>
        </div>
    <?php endif; ?>

    <!-- Form đăng ký học phần -->
    <a href="register.php" class="btn btn-primary">Quay lại đăng ký học phần</a>
    <a href="dashboard.php" class="btn btn-primary">Quay lại trang chính</a>
</div>
</body>
</html>
