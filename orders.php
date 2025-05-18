<?php
session_start();
require 'connection.php';
$con = connection();

// Vérifier si l'utilisateur est un administrateur
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    header('Location: homepage.php');
    exit;
}

// Récupérer toutes les commandes
$sql = "SELECT * FROM sold_items";
$res = mysqli_query($con, $sql);
$orders = mysqli_fetch_all($res, MYSQLI_ASSOC);

mysqli_close($con);
?>
<!DOCTYPE html>
<head>
    <title>All Orders</title>
    <link rel="stylesheet" href="layout/orders.css">
</head>
<body class="admin-page">
<div class="admin-container">
    <?php include 'partials/sidebare.php'; ?>

    <main class="main-content">
        <h1>Toutes les Commandes</h1>

        <table>
            <thead>
            <tr>
                <th>User ID</th>
                <th>Item ID</th>
                <th>Quantity</th>
                <th>Unit Price</th>
                <th>Date</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['UserID']) ?></td>
                    <td><?= htmlspecialchars($order['Item_ID']) ?></td>
                    <td><?= htmlspecialchars($order['Quantity']) ?></td>
                    <td><?= number_format($order['UnitPrice'], 2) ?> MAD</td>
                    <td><?= htmlspecialchars($order['SoldAt']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</div>
</body>
</html>
