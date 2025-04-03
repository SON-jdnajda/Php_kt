    <?php
    session_start();
    include 'db_connect.php'; // Kết nối CSDL

    // Thêm sinh viên
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_sinhvien'])) {
        $maSV = $_POST['MaSV'];
        $hoTen = $_POST['HoTen'];
        $gioiTinh = $_POST['GioiTinh'];
        $ngaySinh = $_POST['NgaySinh'];
        $hinh = $_POST['Hinh'];
        $maNganh = $_POST['MaNganh'];
        $password = password_hash($_POST['Password'], PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO SinhVien (MaSV, HoTen, GioiTinh, NgaySinh, Hinh, MaNganh, Password) VALUES ('$maSV', '$hoTen', '$gioiTinh', '$ngaySinh', '$hinh', '$maNganh', '$password')";
        mysqli_query($conn, $sql);
        header('Location: index.php');
        exit;
    }

    // Xóa sinh viên
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_sinhvien'])) {
        $maSV = $_POST['MaSV'];
        mysqli_query($conn, "DELETE FROM SinhVien WHERE MaSV = '$maSV'");
        header('Location: index.php');
        exit;
    }

    // Sửa sinh viên
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_sinhvien'])) {
        $maSV = $_POST['MaSV'];
        $hoTen = $_POST['HoTen'];
        $gioiTinh = $_POST['GioiTinh'];
        $ngaySinh = $_POST['NgaySinh'];
        $hinh = $_POST['Hinh'];
        $maNganh = $_POST['MaNganh'];
        
        $sql = "UPDATE SinhVien SET HoTen='$hoTen', GioiTinh='$gioiTinh', NgaySinh='$ngaySinh', Hinh='$hinh', MaNganh='$maNganh' WHERE MaSV='$maSV'";
        mysqli_query($conn, $sql);
        header('Location: index.php');
        exit;
    }

    // Đăng nhập
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
        $maSV = $_POST['MaSV'];
        $password = $_POST['Password'];
        $result = mysqli_query($conn, "SELECT * FROM SinhVien WHERE MaSV = '$maSV'");
        $user = mysqli_fetch_assoc($result);
        
        if ($user && password_verify($password, $user['Password'])) {
            $_SESSION['user'] = $maSV;
            header('Location: dashboard.php');
            exit;
        } else {
            echo "Sai thông tin đăng nhập";
        }
    }

    // Truy vấn danh sách sinh viên
    $result = mysqli_query($conn, "SELECT * FROM SinhVien");
    $sinhvien = mysqli_fetch_all($result, MYSQLI_ASSOC);
    ?>
    <!DOCTYPE html>
    <html lang="vi">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Trang Sinh Viên</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    </head>
    <body>
        <nav class="navbar navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">Test1</a>
                <ul class="nav">
                    <li class="nav-item"><a class="nav-link text-light" href="#">Sinh Viên</a></li>
                    <li class="nav-item"><a class="nav-link text-light" href="#">Học Phần</a></li>
                    <li class="nav-item"><a class="nav-link text-light" href="#">Đăng Ký</a></li>
                    <li class="nav-item"><a class="nav-link text-light" href="login.php">Đăng Nhập</a></li>
                </ul>
            </div>
        </nav>
        
        <div class="container mt-4">
            <h2>TRANG SINH VIÊN</h2>
            <a href="add_student.php" class="btn btn-primary mb-3">Add Student</a>
            <table class="table table-bordered text-center">
                <thead class="table-dark">
                    <tr>
                        <th>MaSV</th>
                        <th>HoTen</th>
                        <th>GioiTinh</th>
                        <th>NgaySinh</th>
                        <th>Hinh</th>
                        <th>MaNganh</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($sinhvien as $sv): ?>
                        <tr>
                            <td><?= $sv['MaSV'] ?></td>
                            <td><?= $sv['HoTen'] ?></td>
                            <td><?= $sv['GioiTinh'] ?></td>
                            <td><?= date('d/m/Y', strtotime($sv['NgaySinh'])) ?></td>
                            <td><img src="<?= $sv['Hinh'] ?>" width="100"></td>
                            <td><?= $sv['MaNganh'] ?></td>
                            <td>
                                <a href="edit_student.php?MaSV=<?= $sv['MaSV'] ?>" class="text-primary">Edit</a> |
                                <a href="detail_student.php?MaSV=<?= $sv['MaSV'] ?>" class="text-info">Details</a> |
                                <form action="" method="POST" style="display:inline;">
                                    <input type="hidden" name="MaSV" value="<?= $sv['MaSV'] ?>">
                                    <button type="submit" name="delete_sinhvien" class="btn btn-link text-danger" onclick="return confirm('Bạn có chắc muốn xóa?');">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </body>
    </html>
