<?php
session_start();
include '../includes/db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: index.php?error=invalid_id");
    exit;
}

$id = intval($_GET['id']);

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: index.php?error=product_not_found");
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();

if (!isset($_SESSION['koszyk']) || !is_array($_SESSION['koszyk'])) {
    $_SESSION['koszyk'] = [];
}

$found_index = -1;
foreach ($_SESSION['koszyk'] as $index => $item) {
    if (is_array($item) && isset($item['id']) && $item['id'] == $id) {
        $found_index = $index;
        break;
    }
}

if ($found_index >= 0) {
    $_SESSION['koszyk'][$found_index]['ilosc']++;
} else {
    $_SESSION['koszyk'][] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => floatval($product['price']),
        'ilosc' => 1,
        'image' => $product['image']
    ];
}

header("Location: index.php?added=1");
exit;
?>