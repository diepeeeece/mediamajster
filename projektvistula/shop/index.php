<?php
include '../includes/db.php';
session_start();

// Получаем параметры поиска и категории
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';

// Формируем SQL-запрос
$sql = "SELECT * FROM products WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $sql .= " AND (name LIKE ? OR description LIKE ?)";
    $search_term = "%$search%";
    $params[] = $search_term;
    $params[] = $search_term;
    $types .= "ss";
}

if (!empty($category) && $category !== 'all') {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

$sql .= " ORDER BY created_at DESC";

// Выполняем запрос
$stmt = $conn->prepare($sql);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

$categories_result = $conn->query("SELECT DISTINCT category FROM products ORDER BY category");
$categories = [];
while ($cat = $categories_result->fetch_assoc()) {
    $categories[] = $cat['category'];
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechShop - Najlepsze produkty</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">

        
        <header class="header">
            <nav class="navbar">
                <div class="logo">
                    <i class="fas fa-laptop-code"></i> MediaMajster
                </div>

                <div class="nav-links">
                    <a href="index.php" class="nav-link">
                        <i class="fas fa-home"></i> Strona Główna
                    </a>
                    <a href="cart.php" class="nav-link">
                        <i class="fas fa-shopping-cart"></i> Koszyk
                        <span class="cart-count">
                            <?= count($_SESSION['koszyk'] ?? []); ?>
                        </span>
                    </a>
                </div>
            </nav>
        </header>

        
        <section class="search-section">
            <form method="GET" class="search-form">
                <div class="form-group">
                    <label for="search">Szukaj produktów</label>
                    <input
                        type="text"
                        id="search"
                        name="search"
                        value="<?= htmlspecialchars($search); ?>"
                        placeholder="Czego szukasz?"
                    >
                </div>

                <div class="form-group">
                    <label for="category">Kategoria</label>
                    <select id="category" name="category">
                        <option value="all">Wszystkie kategorie</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat; ?>" <?= $category === $cat ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($cat); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-search"></i> Szukaj
                </button>
            </form>
        </section>

        <?php if (isset($_GET['added'])): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                Produkt został dodany do koszyka!
            </div>
        <?php endif; ?>

        
        <section class="products-grid">
            <?php if ($result->num_rows > 0): ?>
                <?php while ($product = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <img
                            src="../assets/images/<?= htmlspecialchars($product['image']); ?>"
                            alt="<?= htmlspecialchars($product['name']); ?>"
                            class="product-image"
                        >

                        <div class="product-category"><?= htmlspecialchars($product['category']); ?></div>
                        <h3 class="product-title"><?= htmlspecialchars($product['name']); ?></h3>
                        <p class="product-description">
                            <?= htmlspecialchars(substr($product['description'], 0, 100)); ?>...
                        </p>
                        <div class="product-price">
                            <?= number_format($product['price'], 2); ?> zł
                        </div>

                        <a href="add_to_cart.php?id=<?= $product['id']; ?>" class="btn btn-primary">
                            <i class="fas fa-cart-plus"></i> Dodaj do koszyka
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="no-products">
                    <i class="fas fa-search"></i>
                    <h3>Nie znaleziono produktów</h3>
                    <p>Spróbuj zmienić kryteria wyszukiwania</p>
                </div>
            <?php endif; ?>
        </section>

    </div>
</body>
</html>
