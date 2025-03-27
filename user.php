<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$limit = 5; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) $page = 1;
$offset = ($page - 1) * $limit;


$totalQuery = $conn->query("SELECT COUNT(*) AS total FROM nhanvien");
$totalRow = $totalQuery->fetch_assoc();
$totalRecords = $totalRow['total'];
$totalPages = ceil($totalRecords / $limit);

$stmt = $conn->prepare("SELECT nhanvien.Ma_NV, Ten_NV, Phai, Noi_Sinh, Ten_Phong, Luong 
                        FROM nhanvien 
                        JOIN phongban ON nhanvien.Ma_Phong = phongban.Ma_Phong
                        LIMIT ? OFFSET ?");
$stmt->bind_param("ii", $limit, $offset);
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
        .pagination {
            margin-top: 20px;
        }
        .pagination a {
            display: inline-block;
            padding: 8px 12px;
            margin: 2px;
            border: 1px solid #007BFF;
            color: #007BFF;
            text-decoration: none;
            border-radius: 5px;
        }
        .pagination a.active {
            background-color: #007BFF;
            color: white;
        }
        .pagination a:hover {
            background-color: #0056b3;
            color: white;
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

    <!-- Phân trang -->
    <div class="pagination">
        <?php if ($page > 1): ?>
            <a href="?page=<?= $page - 1 ?>">« Trước</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <a href="?page=<?= $i ?>" class="<?= ($i == $page) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($page < $totalPages): ?>
            <a href="?page=<?= $page + 1 ?>">Tiếp »</a>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
