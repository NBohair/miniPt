<?php
session_start();
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] !== 1) {
    header('Location: homepage.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Control Station</title>
  <link rel="stylesheet" href="layout/admin.css">
</head>
<body class="admin-page">
  <div class="admin-container">

   
    <?php include 'partials/sidebare.php'; ?>

    
    <main class="main-content">
      <h1>Welcome to Admin Control Station</h1>
      
     
      <form method="post" action="logout.php" style="margin-top: 20px;">
        <button type="submit" class="btn-submit">Log Out</button>
      </form>
    </main>
    
  </div>
</body>
</html>
