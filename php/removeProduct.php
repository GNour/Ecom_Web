<?php

require_once "./config/connection.php";
require_once "./base/StoreOwner.php";

session_start();

$productId = $_GET["id"];
$storeId = $_SESSION["user"]->getStoreId();

if ($stmt = $connection->query("SELECT path FROM `image` WHERE product_id = " . intval($productId))) {

    while ($image = $stmt->fetch_assoc()) {
        unlink($image["path"]);
    }

    if ($stmt = $connection->prepare("DELETE FROM product WHERE id = ? AND store_id = ?")) {
        $stmt->bind_param("ii", $productId, $storeId);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->affected_rows > 0) {
            echo "Removed Product!";
        } else {
            echo "Already Removed!";
        }
    } else {
        echo $connection->error;
    }

} else {
    echo $connection->error;
}
