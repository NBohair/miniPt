<?php
session_start();
require 'connection.php';
require 'partials/header.php';
$con = connection();

// Always get price_order (if set) from GET
$price_order = $_GET['price_order'] ?? 'asc';

// Get current values from GET
$q      = trim($_GET['q']  ?? '');
$filter = intval($_GET['filter'] ?? 0);

$error  = '';
$items  = [];

// Save new q/filter to session if set
if ($q !== '') $_SESSION['q'] = $q;
if ($filter !== 0) $_SESSION['filter'] = $filter;

// If both q and filter are empty but user set price_order, use last values from session
if ($q === '' && $filter === 0 && isset($_GET['price_order'])) {
    $q = $_SESSION['q'] ?? '';
    $filter = $_SESSION['filter'] ?? 0;
}

// Build the query as before
if ($q === '' && $filter === 0) {
    $error = 'Please enter a search term or select a filter.';
} else {
    // 1) Filter‐only: no q, but filter chosen
    if ($q === '' && $filter !== 0) {
        $sql = "SELECT Item_ID, Name, Description, Image, Price
                FROM items
                WHERE Cat_ID = $filter";
        $sql .= pricesORDER($price_order);
        // 2) Search‐only: q typed, no filter
    } elseif ($q !== '' && $filter === 0) {
        $sql = "SELECT Item_ID, Name, Description, Image, Price
                FROM items
                WHERE 1";
        $terms = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($terms as $word) {
            $w = mysqli_real_escape_string($con, $word);
            $sql .= " AND tags LIKE '%$w%'";
        }
        $sql .= pricesORDER($price_order);
        // 3) Both search + filter
    } else {
        $sql = "SELECT Item_ID, Name, Description, Image, Price
                FROM items
                WHERE Cat_ID = $filter";
        $terms = preg_split('/\s+/', $q, -1, PREG_SPLIT_NO_EMPTY);
        foreach ($terms as $word) {
            $w = mysqli_real_escape_string($con, $word);
            $sql .= " AND tags LIKE '%$w%'";
        }
        $sql .= pricesORDER($price_order);
    }

    // Run the query
    $res = mysqli_query($con, $sql);
    if (!$res) {
        $error = 'Database error: ' . mysqli_error($con);
    } elseif (mysqli_num_rows($res) === 0) {
        $error = 'No items found matching your search.';
    } else {
        $items = mysqli_fetch_all($res, MYSQLI_ASSOC);
    }
}

if ($q === '' && $filter !== 0) {
    // fetch the category name
    $r = mysqli_query($con,
        "SELECT Name FROM categories WHERE ID = $filter LIMIT 1"
    );
    $row = mysqli_fetch_assoc($r);
    $displayTerm = $row['Name'] ?? '';
} else {
    $displayTerm = $q;
}

function pricesORDER($order)
{
    if ($order === 'asc') {
        return " ORDER BY Price ASC";
    } elseif ($order === 'desc') {
        return " ORDER BY Price DESC";
    } else {
        return "";
    }
}

mysqli_close($con);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Search Results – DimaBuy</title>
  <link rel="stylesheet" href="layout/homepage.css">
</head>
<body>
    <?php render_header('Search Results – DimaBuy');?>
  <main class="content">
  <?php if ($error): ?>
    <p class="error"><?= htmlspecialchars($error) ?></p>
  <?php else: ?>
    <h2>Results for “<?= htmlspecialchars($displayTerm) ?>”</h2>
    <section class="products">
      <?php foreach ($items as $p): ?>
        <div class="card">
          <div class="thumb">
            <img
              src="images/<?= htmlspecialchars($p['Image']) ?>"
              alt="<?= htmlspecialchars($p['Name']) ?>">
          </div>
          <div class="info">
            <h3><?= htmlspecialchars($p['Name']) ?></h3>

            <p><?= htmlspecialchars($p['Description']) ?></p>
                        <!-- New price line -->
            <p class="price">
              Price: <?= number_format($p['Price'], 2) ?> MAD
            </p>
            <a href="product.php?id=<?= $p['Item_ID']?>"
               class="btn-buy">View Details</a>
          </div>
        </div>

      <?php endforeach; ?>
    </section>
  <?php endif; ?>
</main>




</body>
</html>
