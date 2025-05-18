<?php
// layout.php

// 1) Shared startup: session + DB
require_once __DIR__ . '/../connection.php';
$con = connection();

// 2) Fetch categories for the search filter
$catRes = mysqli_query($con, "SELECT ID, Name FROM categories WHERE Visibility = 1");
$categories = [];
while ($c = mysqli_fetch_assoc($catRes)) {
    $categories[] = $c;
}
mysqli_close($con);

// 3) Render the opening HTML + header
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
      <form action="search.php" method="get">
        <div class="search-container">
          <select name="filter" class="search-filter" onchange="this.form.submit()">
            <option  disabled selected hidden>Filter</option>
            <?php foreach ($categories as $cat): ?>
              <option value="<?= $cat['ID'] ?>">
                <?= htmlspecialchars($cat['Name']) ?>
              </option>
            <?php endforeach; ?>
          </select>
          <div class="search-box">
            <input type="text" name="q" class="search-input" placeholder="Search…">
            <img src="images/search-icon.png" alt="Search" class="search-icon"
                 onclick="this.closest('form').submit()">
          </div>
        </div>

      </form>
    </div>
    <?php if (basename($_SERVER['PHP_SELF']) !== 'homepage.php'): ?>
        <div class="header-right">
            <style>
                .price-filter {
                    display: flex;
                    align-items: center;
                    gap: 22px;
                    background: #fff;
                    padding: 12px 22px;
                    border-radius: 14px;

                    margin-top: 12px;
                }

                .price-filter label {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    font-size: 1rem;
                    color: #232F3E; /* Amazon dark blue */
                    font-weight: 500;
                    cursor: pointer;
                    transition: color 0.2s;
                    user-select: none;
                }

                .price-filter input[type="radio"] {
                    accent-color: #febd69; /* Amazon orange */
                    width: 18px;
                    height: 18px;
                    margin-right: 3px;
                    cursor: pointer;
                }

                .price-filter input[type="radio"]:checked + span {
                    color: #ff9900; /* Highlight label when selected */
                    font-weight: 600;
                }

                .price-filter-btn {
                    background: #febd69;
                    color: #131921;
                    border: none;
                    border-radius: 8px;
                    padding: 7px 18px;
                    font-size: 1rem;
                    font-weight: 600;
                    cursor: pointer;
                    transition: background 0.2s, color 0.2s, ;
                    margin-left: 10px;

                }

                .price-filter-btn:hover {
                    background: #ff9900;
                    color: #fff;
                }
            </style>
            <form action="search.php" method="get" class="price-filter">
                <label>
                    <input type="radio" name="price_order" value="asc" >
                    <span>Prix Croissant</span>
                </label>
                <label>
                    <input type="radio" name="price_order" value="desc" >
                    <span>Prix Décroissant</span>
                </label>
                <button type="submit" class="price-filter-btn">Trier</button>
            </form>
        </div>
          <?php endif;?>
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

// 4) Render the closing footer + HTML
function render_footer() {
    ?>
  <footer class="footer">
    © DimaBuy 2025
  </footer>
</body>
</html>
    <?php
}
