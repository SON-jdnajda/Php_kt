<?php
session_start();
include 'db_connect.php';

// Kiểm tra nếu chưa có giỏ hàng thì tạo giỏ hàng rỗng
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Xử lý đăng ký học phần
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $maHP = $_POST['MaHP'];
    if (!in_array($maHP, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $maHP;
    }
}

// Lấy danh sách học phần từ database
$query = "SELECT * FROM HocPhan";
$result = mysqli_query($conn, $query);
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
                <?php while ($hp = mysqli_fetch_assoc($result)): ?>
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
