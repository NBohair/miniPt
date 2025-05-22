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
</head>
<body>
  <?php render_header('Search Results – DimaBuy');?>

  <main class="content">
    <h2>Your Profile</h2>
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
      
      <button type="submit" name="logout" value="1" style="margin-top: 10px; padding: 14px 0; background: #e53935; color: #fff;font-size: 1rem;font-weight: 600;border: none; border-radius: 8px; cursor: pointer; transition: background 0.3s ease, transform 0.2s ease;
  ">LogOut</button>

      
    </form>
  </main>

  <?php render_footer();?>
</body>
</html>
