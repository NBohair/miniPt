<?php
session_start();

if (isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}

require 'connection.php';
require 'partials/header.php';
$con = connection();

if (empty($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
$uid = $_SESSION['user_id'];
$msg = '';

if (isset($_POST['fullname']) && isset($_POST['email'])) {
    $fullname = $_POST['fullname'];
    $email    = $_POST['email'];
    $password = $_POST['password'];

    $sql = "UPDATE users SET FullName='$fullname', Email='$email'";
    if (!empty($password)) {
        $sql .= ", Password='$password'";
    }
    $sql .= " WHERE UserID = $uid";

    if (mysqli_query($con, $sql)) {
        $msg = 'Profile updated successfully.';
        $_SESSION['user'] = $fullname;
    } else {
        $msg = 'Error: ' . mysqli_error($con);
    }
}

// Fetch current user info
$res = mysqli_query($con,
    "SELECT Username, Email, FullName, Date
     FROM users
     WHERE UserID = $uid
     LIMIT 1"
);
$user = mysqli_fetch_assoc($res);
mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Profile DimaBuy</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="layout/profile.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #f4f7fa;
      color: #333;
      padding: 20px;
    }
    .profile-container {
      max-width: 600px;
      margin: 40px auto;
      background: #ffffff;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    .profile-title {
      text-align: center;
      font-size: 2rem;
      margin-bottom: 30px;
      color: #333333;
    }
    .profile-form {
      display: grid;
      grid-template-columns: 1fr;
      row-gap: 20px;
    }
    .profile-form label {
      display: flex;
      flex-direction: column;
      font-size: 0.9rem;
      color: #555555;
    }
    .profile-form input {
      margin-top: 8px;
      padding: 12px 16px;
      font-size: 1rem;
      border: 1px solid #e0e0e0;
      border-radius: 8px;
      transition: border-color 0.3s ease, box-shadow 0.3s ease;
    }
    .profile-form input:focus {
      border-color: #ffffff;
      box-shadow: 0 0 0 3px rgba(108, 99, 255, 0.2);
      outline: none;
    }
    .profile-form .btn {
      margin-top: 10px;
      padding: 14px 0;
      background: #2980b9;
      color: #fff;
      font-size: 1rem;
      font-weight: 600;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: background 0.3s ease, transform 0.2s ease;
    }
    .profile-form .btn:hover {
      background: #3498db;
      transform: translateY(-2px);
    }
    .success {
      background: #e6ffed;
      border: 1px solid #a1eac4;
      color: #2a662a;
      padding: 12px;
      border-radius: 6px;
      text-align: center;
      margin-bottom: 20px;
    }
    .btn {
      display: inline-block;
      padding: 10px 20px;
      background-color: #2980b9;
      color: #fff;
      text-decoration: none;
      border-radius: 5px;
      text-align: center;
      transition: background-color 0.3s, transform 0.2s;
      border: none;
      cursor: pointer;
      font-size: 1rem;
      font-weight: 600;
    }
    .btn:hover {
      background-color: #3498db;
      transform: translateY(-2px);
    }
    .btn:active {
      background-color: #1f6391;
      transform: translateY(0);
    }
  </style>
</head>
<body>
  <?php render_header('Your Profile – DimaBuy');?>

  <main class="profile-container">
    <h2 class="profile-title">Your Profile</h2>
    <?php if ($msg): ?>
      <p class="success"><?= htmlspecialchars($msg) ?></p>
    <?php endif; ?>

    <form method="post" class="profile-form">
      <label>
        Username
        <input type="text"
               value="<?= htmlspecialchars($user['Username']) ?>"
               disabled>
      </label>

      <label>
        Full Name
        <input type="text"
               name="fullname"
               value="<?= htmlspecialchars($user['FullName']) ?>"
               >
      </label>

      <label>
        Email
        <input type="email"
               name="email"
               value="<?= htmlspecialchars($user['Email']) ?>"
               >
      </label>

      <label>
        New Password
        <input type="password"
               name="password"
               placeholder="Leave blank to keep current">
      </label>

      <label>
        Member Since
        <input type="text"
               value="<?= htmlspecialchars($user['Date']) ?>"
               disabled>
      </label>

      <button type="submit" class="btn">Save Changes</button>
      
      <button type="submit" name="logout" value="1" class="btn" style="background: #e53935; margin-top: 10px;">Log Out</button>
    </form>

    <form action="historique_commandes.php" method="get" style="text-align: center; margin-top: 20px;">
        <button type="submit" class="btn">Historique des Commandes</button>
    </form>
  </main>

  <?php render_footer();?>
</body>
</html>
