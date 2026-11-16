<?php
require_once __DIR__ . "/includes/items.php";

$isNew = isset($_GET["new"]);
$id = isset($_GET["id"]) ? (int) $_GET["id"] : null;
$item = null;
if ($id) {
    $item = getItem($id);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["delete"]) && isset($_POST["id"])) {
        deleteItem((int) $_POST["id"]);
        header("Location: admin.php");
        exit();
    }

    $name = trim($_POST["name"] ?? "");
    $price = floatval($_POST["price"] ?? 0);
    $quantity = intval($_POST["amount"] ?? 0);
    $description = trim($_POST["description"] ?? "");
    $image = null;

    if (!empty($_FILES["image_file"]) && $_FILES["image_file"]["error"] === UPLOAD_ERR_OK) {
        $image = saveUploadedImage($_FILES["image_file"]);
    }

    if (empty($image) && !empty($_POST["image_url"])) {
        $image = trim($_POST["image_url"]);
    }

    $data = [
        "name" => $name,
        "price" => $price,
        "description" => $description,
        "image" => $image,
        "quantity" => $quantity,
    ];

    if (!empty($_POST["id"])) {
        $iid = (int) $_POST["id"];
        updateItem($iid, $data);
    } else {
        createItem($data);
    }
    header("Location: admin.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Product editor</title>
        <link rel="stylesheet" href="css/output.css" />
        <link rel="icon" type="image/x-icon" href="img/favicon.ico" />
    </head>
    <body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
        <div class="bg-white rounded-2xl shadow-md w-full max-w-5xl p-8">
            <div class="flex items-center justify-between mb-6">
                <h1 id="title" class="text-2xl font-bold text-gray-800 mb-6"><?= $item
                    ? "Edit product"
                    : "Add a product" ?></h1>
                <a href="admin.php" class="py-4 px-8 text-xl items-center rounded-full bg-yellow-300">← Back</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div class="flex flex-col items-center">
                    <div class="w-64 h-64 border border-gray-300 rounded-xl overflow-hidden bg-gray-50 flex items-center justify-center">
                        <?php if ($item && $item["image"]): ?>
                            <img src="<?= htmlspecialchars($item["image"]) ?>" alt="<?= htmlspecialchars(
    $item["name"],
) ?>" class="object-cover w-full h-full">
                        <?php else: ?>
                            <span class="text-gray-400">No image</span>
                        <?php endif; ?>
                    </div>

                    <p class="text-sm text-gray-600 mt-4">Upload image (preferred) or provide image URL below.</p>
                    <form method="post" enctype="multipart/form-data" id="product-form">
                        <input type="file" name="image_file" accept="image/*" class="bg-gray-300 items-center mt-4" />
                </div>

                <div>
                        <?php if ($item): ?>
                            <input type="hidden" name="id" value="<?= $item["id"] ?>">
                        <?php endif; ?>
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">name</label>
                            <input type="text" id="name" name="name" required value="<?= $item
                                ? htmlspecialchars($item["name"])
                                : "" ?>" class="w-full border border-gray-300 rounded-xl px-4 py-2" />
                        </div>
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-1">Price (€)</label>
                            <input type="number" id="price" name="price" step="0.01" required value="<?= $item
                                ? htmlspecialchars($item["price"])
                                : "" ?>" class="w-full border border-gray-300 rounded-xl px-4 py-2" />
                        </div>
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-1">Amount in storage</label>
                            <input type="number" id="amount" name="amount" required value="<?= $item
                                ? (int) $item["quantity"]
                                : 0 ?>" class="w-full border border-gray-300 rounded-xl px-4 py-2" />
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1">description</label>
                            <textarea id="description" name="description" rows="4" class="w-full border border-gray-300 rounded-xl px-4 py-2"><?= $item
                                ? htmlspecialchars($item["description"])
                                : "" ?></textarea>
                        </div>

                        <div class="pt-4 space-y-2">
                            <label for="image_url" class="block text-sm font-medium text-gray-700 mb-1">Image URL (optional)</label>
                            <input type="text" id="image_url" name="image_url" value="<?= $item
                                ? htmlspecialchars($item["image"])
                                : "" ?>" class="w-full border border-gray-300 rounded-xl px-4 py-2" />
                            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-xl">Save</button>
                        </div>
                        </form>

                        <?php if ($item): ?>
                            <form method="post" onsubmit="return confirm('Delete this product?');">
                                <input type="hidden" name="id" value="<?= $item["id"] ?>">
                                <button type="submit" name="delete" class="w-full mt-2 bg-red-500 hover:bg-red-600 text-white py-2 rounded-xl">Remove</button>
                            </form>
                        <?php endif; ?>
                </div>
            </div>
        </div>
    </body>
</html>
