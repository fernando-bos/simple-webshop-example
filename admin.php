<?php

require_once __DIR__ . "/includes/items.php";
$items = getAllItems();
$orders = getOrders();
$showOrder = isset($_GET["show_order"]) ? (int) $_GET["show_order"] : null;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Admin Dashboard</title>
    <link href="css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
  </head>
  <body class="m-0 p-0 bg-gray-100 text-gray-900">
    <nav class="flex justify-between items-center bg-black p-4">
      <h1 class="text-white text-xl font-semibold">Admin Dashboard</h1>
      <div class="flex gap-2">
        <a class="rounded-xl text-black cursor-pointer px-5 py-2 hover:bg-orange-500 transition duration-200 bg-orange-400" href="index.php">Products</a>
      </div>
    </nav>

    <div id="content" class="flex flex-col p-6 gap-6">
      <div class="w-full max-w-4xl mx-auto border border-gray-300 bg-white shadow-md">
        <div class="border-b border-gray-300">
          <div class="flex justify-between items-center p-4 bg-gray-100">
            <span class="font-semibold">Products</span>
            <a href="editpage.php?new=1" class="px-4 font-semibold bg-yellow-300 py-2 rounded-full">New</a>
          </div>
        </div>
        <div class="px-0 py-0 bg-white text-gray-700">
          <div class="grid grid-cols-3 px-4">
            <p class="text-sm py-2">Name:</p>
            <p class="text-sm border-x border-gray-300 py-2 px-4 text-right">Quantity:</p>
            <p class="text-sm py-2 text-right">More info:</p>
          </div>
          <?php foreach ($items as $item): ?>
            <div class="grid grid-cols-3 px-4 border-t border-gray-300">
              <p class="text-sm py-2"><?= htmlspecialchars($item["name"]) ?></p>
              <p class="text-sm border-x border-gray-300 py-2 px-4 text-right"><?= (int) $item["quantity"] ?></p>
              <div class="flex justify-end items-center gap-2 py-2">
                <a href="editpage.php?id=<?= $item["id"] ?>" class="px-2 py-1 rounded-full bg-yellow-300">Details</a>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>

      <div class="w-full max-w-4xl mx-auto border border-gray-300 bg-white shadow-md">
        <div class="border-b border-gray-300">
          <div class="flex justify-between items-center p-4 bg-gray-100">
            <span class="font-semibold">Orders</span>
          </div>
        </div>
        <div class="py-0 bg-white text-gray-700">
          <div class="grid grid-cols-5 px-4 py-2 font-semibold">
            <div>Order number</div>
            <div>Date</div>
            <div class="text-right">Quantity</div>
            <div class="text-right">Price</div>
            <div class="text-right">More</div>
          </div>
          <?php foreach ($orders as $order):

              $details = json_decode($order["items"], true);
              $quantity = 0;
              foreach ($details as $d) {
                  $quantity += $d["quantity"];
              }
              ?>
            <div class="grid grid-cols-5 px-4 py-2 border-t">
              <div><?= $order["id"] ?></div>
              <div><?= date("Y-m-d", strtotime(htmlspecialchars($order["time"]))) ?></div>
              <div class="text-right"><?= $quantity ?></div>
              <div class="text-right"><?= number_format($order["total_price"], 2) ?> €</div>
              <div class="text-right"><a href="admin.php?show_order=<?= $order[
                  "id"
              ] ?>" class="px-4 font-semibold bg-yellow-300 py-2 rounded-full">More</a></div>
            </div>
            <div id="order-<?= $order["id"] ?>" class="px-4"<?php if ($showOrder !== $order["id"]) {
    echo ' style="display:none"';
} ?> >
              <div class="grid grid-cols-4 border-t border-gray-300 py-2">
                <div>item-name</div>
                <div class="text-right">Quantity</div>
                <div class="text-right">single-Price</div>
                <div class="text-right">Total-Price</div>
              </div>
              <?php foreach ($details as $d): ?>
                <div class="grid grid-cols-4 py-2 border-t">
                  <div><?= htmlspecialchars($d["name"]) ?></div>
                  <div class="text-right"><?= $d["quantity"] ?></div>
                  <div class="text-right"><?= number_format($d["price"], 2) ?> €</div>
                  <div class="text-right"><?= number_format($d["price"] * $d["quantity"], 2) ?> €</div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php
          endforeach; ?>
        </div>
      </div>
    </div>
  </body>
</html>
