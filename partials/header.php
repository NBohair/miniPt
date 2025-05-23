<?php
require_once __DIR__ . '/../connection.php';
$con = connection();

$catRes = mysqli_query($con, "SELECT ID, Name FROM categories WHERE Visibility = 1");
$categories = [];
while ($c = mysqli_fetch_assoc($catRes)) {
    $categories[] = $c;
}
mysqli_close($con);

function render_header($title = 'DimaBuy') {
    global $categories;
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title><?= htmlspecialchars($title) ?></title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="layout/homepage.css">
  <style>
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 40px;
      background: #fff;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .header-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .logo-img {
      height: 40px;
      width: auto;
    }
    .site-name {
      font-size: 1.5rem;
      font-weight: bold;
      color: #333;
    }
    .header-center {
      display: flex;
      align-items: center;
      gap: 20px;
    }
    .search-container {
      display: flex;
      align-items: center;
      border: 1px solid #ccc;
      border-radius: 25px;
      overflow: hidden;
    }
    .search-filter, .sort-dropdown {
      padding: 10px;
      font-size: 1rem;
      border: none;
      background: transparent;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }
   
    .search-box {
      position: relative;
      flex: 1;
    }
    .search-input {
      width: 100%;
      padding: 10px;
      border: none;
      outline: none;
    }
    .search-icon {
      position: absolute;
      top: 50%;
      right: 10px;
      transform: translateY(-50%);
      width: 20px;
      opacity: 0.7;
    }
    .header-right {
      display: flex;
      align-items: center;
      gap: 15px;
    }
    .header-right .icon img {
      height: 30px;
      cursor: pointer;
    }
    .search-filter, .sort-dropdown {
      padding: 10px;
      font-size: 1rem;
      border: none;
      background: transparent;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.3s ease;
      border-radius: 25px;
    }
  </style>
</head>
<body>
  <header class="header">
    <div class="header-left">
      <a href="homepage.php" class="logo-link">
        <img src="images/logo.png" alt="Logo" class="logo-img">
      </a>
      <span class="site-name">DimaBuy</span>
    </div>
    <div class="header-center">
      <form action="search.php" method="get" style="display: flex; align-items: center; gap: 10px;">
        <div class="search-container">
          <select name="filter" class="search-filter">
            <option value="" disabled selected hidden>Filter</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['ID'] ?>">
                <?= htmlspecialchars($cat['Name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="search-box">
            <input type="text" name="q" class="search-input" placeholder="Search…">
            <img src="images/search-icon.png" alt="Search" class="search-icon">
          </div>
        </div>
        <select name="price_sort" class="sort-dropdown">
          <option value="0">Sort by price</option>
          <option value="asc" <?= (($_GET['price_sort'] ?? '')==='asc')?'selected':'' ?>>Low → High</option>
          <option value="desc" <?= (($_GET['price_sort'] ?? '')==='desc')?'selected':'' ?>>High → Low</option>
        </select>
      </form>
    </div>
    <div class="header-right">
      <a href="profile.php" class="icon profile">
        <img src="images/profile.png" alt="Profile">
      </a>
      <a href="cartuser.php" class="icon cart">
        <img src="images/cart.png" alt="Cart">
      </a>
    </div>
  </header>
<?php
}

function render_footer() {
    ?>
  <footer class="footer">
    © DimaBuy 2025
  </footer>
</body>
</html>
    <?php
}
