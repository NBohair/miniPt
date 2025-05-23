<?php
session_start();
require 'connection.php'; 
$con = connection(); 

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = intval($_SESSION['user_id']);
$sql = "SELECT * FROM sold_items WHERE UserID = $user_id ORDER BY SoldAt DESC";
$result = mysqli_query($con, $sql);

if (!$result) {
    die('Database query failed: ' . mysqli_error($con));
}


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order History</title>
    <link rel="stylesheet" href="layout/homepage.css"> 
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f7fa;
            color: #333;
            padding: 20px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #2980b9;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background-color: #3498db;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Your Order History</h1>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Item ID</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($order = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($order['Item_ID']) ?></td>
                        <td><?= htmlspecialchars($order['Quantity']) ?></td>
                        <td><?= number_format($order['UnitPrice'], 2) ?> MAD</td>
                        <td><?= htmlspecialchars($order['SoldAt']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align: center;">You have no orders yet.</p>
    <?php endif; ?>

    <div class="back-link">
        <a href="profile.php" class="btn">Back to Profile</a>
    </div>
</body>
</html>

<?php
mysqli_close($con); 
?>
