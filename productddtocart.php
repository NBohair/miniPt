<?php
session_start();
require 'connection.php';
$con = connection();

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$uid    = intval($_SESSION['user_id']);
$itemId = intval($_GET['id'] ?? 0);
$qty    = max(1, intval($_GET['quantity'] ?? 1));

if (!$itemId) {
    header('Location: homepage.php');
    exit;
}

$sql = "SELECT Quantity
        FROM cart
        WHERE UserID = $uid
          AND Item_ID = $itemId
        LIMIT 1";
$res = mysqli_query($con, $sql);

if ($res && mysqli_num_rows($res) === 1) {
    mysqli_query($con,
      "UPDATE cart
         SET Quantity = Quantity + $qty
       WHERE UserID = $uid AND Item_ID = $itemId"
    );
} else {
    mysqli_query($con,
      "INSERT INTO cart (UserID, Item_ID, Quantity)
       VALUES ($uid, $itemId, $qty)"
    );
}

mysqli_close($con);

header('Location: cartuser.php');
exit;
