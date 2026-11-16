<?php
session_start();
require_once __DIR__ . '/includes/items.php';
$items = getAllItems();
$cart = $_SESSION['cart'] ?? [];
$cartCount = array_sum($cart);
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Webshop</title>
    <link href="css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
  </head>
  <body class="m-0 p-0 bg-gray-100 text-gray-900">
    <nav class="flex items-center justify-between bg-black p-4">
      <h1 class="text-white text-xl font-semibold">The Stylish Webshop</h1>
      <div class="flex gap-2">
        <a class="rounded-xl text-black cursor-pointer px-5 py-2 hover:bg-orange-500 transition duration-200 bg-orange-400" href="index.php">Products</a>
        <a id="cart-link" class="relative rounded-xl text-black cursor-pointer px-5 py-2 hover:bg-orange-500 transition duration-200 bg-orange-400" href="cart.php">Cart<?php if ($cartCount > 0) echo ' (' . $cartCount . ')'; ?></a>
      </div>
    </nav>
    <div class="flex flex-wrap justify-center gap-4 px-4 pb-10 pt-6" id="products">
      <?php foreach ($items as $item): ?>
        <div class="product flex flex-col justify-between w-[300px] h-[500px] m-2 border border-black p-4 box-border rounded shadow">
          <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="w-full h-60 object-cover rounded mb-2">
          <h3 class="text-lg font-semibold mb-1"><?= htmlspecialchars($item['name']) ?></h3>
          <p class="text-gray-600 mb-2"><?= htmlspecialchars($item['description']) ?></p>
          <div class="flex justify-between items-center mt-auto">
            <p class="text-base font-medium text-black"><?= number_format($item['price'], 2) ?> €</p>
            <form method="post" action="cart.php">
              <input type="hidden" name="action" value="add">
              <input type="hidden" name="id" value="<?= $item['id'] ?>">
              <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition" type="submit">Add to Cart</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </body>
</html>
