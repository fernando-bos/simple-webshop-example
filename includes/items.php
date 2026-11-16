<?php
require_once "database/db.php";

function getAllItems()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM items ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getItem($id)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM items WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createItem($data)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO items (name, price, description, image, quantity) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$data["name"], $data["price"], $data["description"], $data["image"], $data["quantity"]]);
    return $pdo->lastInsertId();
}

function updateItem($id, $data)
{
    global $pdo;
    $stmt = $pdo->prepare(
        "UPDATE items SET name = ?, price = ?, description = ?, image = ?, quantity = ? WHERE id = ?",
    );
    return $stmt->execute([
        $data["name"],
        $data["price"],
        $data["description"],
        $data["image"],
        $data["quantity"],
        $id,
    ]);
}

function deleteItem($id)
{
    global $pdo;
    $stmt = $pdo->prepare("DELETE FROM items WHERE id = ?");
    return $stmt->execute([$id]);
}

function createOrder($cart, $totalPrice)
{
    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO orders (time, total_price, items) VALUES (?, ?, ?)");
    $stmt->execute([date("c"), $totalPrice, json_encode($cart)]);
    return $pdo->lastInsertId();
}

function getOrders()
{
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM orders ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function saveUploadedImage($file)
{
    if (!isset($file) || $file["error"] !== UPLOAD_ERR_OK) {
        return null;
    }
    $uploadsDir = __DIR__ . "/../img";
    if (!is_dir($uploadsDir)) {
        mkdir($uploadsDir, 0755, true);
    }
    $ext = pathinfo($file["name"], PATHINFO_EXTENSION);
    $filename = uniqid("img_") . "." . $ext;
    $dest = $uploadsDir . "/" . $filename;
    if (move_uploaded_file($file["tmp_name"], $dest)) {
        return "img/" . $filename;
    }
    return null;
}
