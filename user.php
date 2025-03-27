<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$stmt = $conn->prepare("SELECT nhanvien.Ma_NV, Ten_NV, Phai, Noi_Sinh, Ten_Phong, Luong 
                        FROM nhanvien 
                        JOIN phongban ON nhanvien.Ma_Phong = phongban.Ma_Phong");
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh Sách Nhân Viên</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
            margin: 0;
            padding: 20px;
            text-align: center;
        }
        .container {
            max-width: 800px;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }
        th {
            background: #007bff;
            color: white;
        }
        td img {
            width: 25px;
            vertical-align: middle;
        }
        .logout {
            color: white;
            background: red;
            padding: 8px 15px;
            border-radius: 5px;
            text-decoration: none;
        }
        .logout:hover {
            background: darkred;
        }
    </style>
</head>
<body>

<div class="container">
    <p>Xin chào, <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong> | 
        <a href="logout.php" class="logout">Đăng xuất</a>
    </p>

    <h2>Danh Sách Nhân Viên</h2>

    <table>
        <tr>
            <th>Mã NV</th>
            <th>Tên Nhân Viên</th>
            <th>Giới tính</th>
            <th>Nơi Sinh</th>
            <th>Phòng Ban</th>
            <th>Lương</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['Ma_NV']) ?></td>
                <td><?= htmlspecialchars($row['Ten_NV']) ?></td>
                <td>
                    <img src="asset/<?= $row['Phai'] == 'NU' ? 'woman.gif' : 'man.gif' ?>" alt="gender">
                </td>
                <td><?= htmlspecialchars($row['Noi_Sinh']) ?></td>
                <td><?= htmlspecialchars($row['Ten_Phong']) ?></td>
                <td><?= number_format($row['Luong']) ?> VND</td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

</body>
</html>
