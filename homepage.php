<?php
session_start();
require 'connection.php';
$con = connection();

$sql = "SELECT Item_ID, Name, Description, Image ,Price FROM items LIMIT 9";
$res = mysqli_query($con, $sql);
$products = [];
while ($row = mysqli_fetch_assoc($res)) {
    $products[] = $row;
}

$slides = [
  'images/slide1.jpg',
  'images/slide2.jpg',
  'images/slide3.jpg',
];

$catRes = mysqli_query($con, "SELECT ID, Name FROM categories WHERE Visibility = 1");
$categories = [];
while ($c = mysqli_fetch_assoc($catRes)) {
    $categories[] = $c;
}

mysqli_close($con);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DimaBuy – Home</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="layout/homepage.css">
</head>
<body>

  <?php include 'partials/header.php'; ?>
  <?php render_header('DimaBuy');?>

   
  <section class="slider">
  <button class="arrow prev">&#10094;</button>
  <img id="slide-img" src="images/slide1.jpg">
  <button class="arrow next">&#10095;</button>
  </section>


  <section class="products">
  <?php foreach ($products as $p): ?>
    <div class="card">
      <div class="thumb">
        <img
          src="images/<?= htmlspecialchars($p['Image'])?>"
          alt="<?= htmlspecialchars($p['Name'])?>">
      </div>
      <div class="info">
        <h3><?= htmlspecialchars($p['Name'])?></h3>
        <p><?= htmlspecialchars($p['Description'])?></p>
        <p class="price">
          Price: <?= number_format($p['Price'],2)?> MAD
        </p>
        <a 
          href="product.php?id=<?= $p['Item_ID']?>" 
          class="btn-buy">
          View Details
        </a>
      </div>
    </div>
  <?php endforeach; ?>
</section>



  <?php render_footer();?>

  <script>
    const slides = <?= json_encode($slides) ?>;
    let idx = 0;
    const imgEl = document.getElementById('slide-img');
    document.querySelector('.prev').onclick = () => {
      idx = (idx - 1 + slides.length) % slides.length;
      imgEl.src = slides[idx];
    };
    document.querySelector('.next').onclick = () => {
      idx = (idx + 1) % slides.length;
      imgEl.src = slides[idx];
    };
    setInterval(() => {
      idx = (idx + 1) % slides.length;
      imgEl.src = slides[idx];
    }, 30000);
  </script>

</body>
</html>
