<?php
session_start();
require_once __DIR__ . "/includes/items.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $action = $_POST["action"] ?? null;
    if ($action === "add" && isset($_POST["id"])) {
        $id = (int) $_POST["id"];
        if (!isset($_SESSION["cart"])) {
            $_SESSION["cart"] = [];
        }
        if (!isset($_SESSION["cart"][$id])) {
            $_SESSION["cart"][$id] = 0;
        }
        $_SESSION["cart"][$id]++;
        header("Location: index.php");
        exit();
    }
    if ($action === "update" && isset($_POST["id"], $_POST["quantity"])) {
        $id = (int) $_POST["id"];
        $q = max(0, intval($_POST["quantity"]));
        if ($q <= 0) {
            unset($_SESSION["cart"][$id]);
        } else {
            $_SESSION["cart"][$id] = $q;
        }
        header("Location: cart.php");
        exit();
    }
    if ($action === "remove" && isset($_POST["id"])) {
        $id = (int) $_POST["id"];
        unset($_SESSION["cart"][$id]);
        header("Location: cart.php");
        exit();
    }
    if ($action === "order") {
        $cart = $_SESSION["cart"] ?? [];
        if (empty($cart)) {
            header("Location: cart.php?msg=empty");
            exit();
        }
        $items = [];
        $total = 0.0;
        foreach ($cart as $id => $qty) {
            $it = getItem($id);
            if ($it) {
                $items[] = [
                    "id" => $it["id"],
                    "name" => $it["name"],
                    "price" => (float) $it["price"],
                    "quantity" => (int) $qty,
                ];
                $total += $it["price"] * $qty;
            }
        }
        createOrder($items, $total);
        unset($_SESSION["cart"]);
        header("Location: cart.php?ordered=1");
        exit();
    }
}

$cart = $_SESSION["cart"] ?? [];
$cartItems = [];
$totalPrice = 0.0;
foreach ($cart as $id => $qty) {
    $item = getItem($id);
    if ($item) {
        $item["qty"] = $qty;
        $item["line_total"] = $item["price"] * $qty;
        $totalPrice += $item["line_total"];
        $cartItems[] = $item;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Cart</title>
    <link href="css/output.css" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="img/favicon.ico">
  </head>
  <body class="m-0 p-0">
    <nav class="flex justify-end gap-2 bg-black p-3">
      <a class="rounded-xl text-black cursor-pointer px-5 duration-200 py-2 hover:bg-orange-500 transition bg-orange-400" href="index.php">products</a>
      <a class="rounded-xl text-black cursor-pointer px-5 duration-200 py-2 hover:bg-orange-500 transition bg-orange-400" href="cart.php">cart</a>
    </nav>
    <div class="flex flex-col p-6">
      <?php if (isset($_GET["ordered"])): ?>
        <div class="p-6 text-center bg-green-100 rounded-lg shadow">
          <h2 class="text-2xl font-bold text-green-800 mb-2">Order Successful!</h2>
          <p class="text-green-700">Your order has been placed successfully. Thank you for shopping with us!</p>
        </div>
      <?php elseif (empty($cartItems)): ?>
        <div class="p-6 text-center bg-yellow-100 rounded-lg shadow">
          <h2 class="text-xl font-bold">Your cart is empty</h2>
          <p class="text-gray-700">Add products from the products page.</p>
        </div>
      <?php else: ?>
        <table class="w-full border-collapse">
          <thead>
            <tr class="bg-gray-100 text-left">
              <th class="p-2 border-b">Item</th>
              <th class="p-2 border-b">Name</th>
              <th class="p-2 border-b text-center">Amount</th>
              <th class="p-2 border-b text-right">Price</th>
              <th class="p-2 border-b"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cartItems as $it): ?>
              <tr class="border-b">
                <td class="p-2"><img class="w-[50px] h-[50px]" src="<?= htmlspecialchars(
                    $it["image"],
                ) ?>" alt="<?= htmlspecialchars($it["name"]) ?>"></td>
                <td class="p-2"><h3 class="text-lg font-medium"><?= htmlspecialchars($it["name"]) ?></h3></td>
                <td class="p-2 text-center">
                  <form method="post" action="cart.php" class="inline-block">
                    <input type="hidden" name="action" value="update">
                    <input type="hidden" name="id" value="<?= $it["id"] ?>">
                    <input class="w-16 text-center" type="number" name="quantity" value="<?= $it["qty"] ?>" min="0">
                    <button class="ml-2 bg-blue-500 text-white px-2 py-1 rounded" type="submit">Update</button>
                  </form>
                </td>
                <td class="p-2 text-right"><p><?= number_format($it["line_total"], 2) ?> €</p></td>
                <td class="p-2">
                  <form method="post" action="cart.php">
                    <input type="hidden" name="action" value="remove">
                    <input type="hidden" name="id" value="<?= $it["id"] ?>">
                    <button class="px-2 py-1 bg-red-500 text-white rounded" type="submit">remove</button>
                  </form>
                </td>
              </tr>
            <?php endforeach; ?>
            <tr class="bg-gray-200 font-semibold">
              <td colspan="2" class="p-2 text-right"><h3>Total:</h3></td>
              <td colspan="2" class="p-2 text-right"><p id="totalPrice"><?= number_format($totalPrice, 2) ?> €</p></td>
              <td class="p-2">
                <form method="post" action="cart.php">
                  <input type="hidden" name="action" value="order">
                  <button class="bg-blue-500 text-white px-4 py-2 rounded" type="submit">Buy</button>
                </form>
              </td>
            </tr>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </body>
</html>
