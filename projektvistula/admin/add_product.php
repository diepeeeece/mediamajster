<?php
session_start();
include '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';
    $price = $_POST['price'] ?? '';
    $category = $_POST['category'] ?? '';
    
    $image = 'default.jpg';
    
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../assets/images/';
        $image = time() . '_' . basename($_FILES['image']['name']);
        move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $image);
    }
    
    $stmt = $conn->prepare("INSERT INTO products (name, description, price, image, category) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssdss", $name, $description, $price, $image, $category);
    
    if ($stmt->execute()) {
        $success = "Produkt został dodany pomyślnie!";
    } else {
        $error = "Błąd podczas dodawania produktu: " . $conn->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dodaj Produkt - Admin</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="header">
            <nav class="navbar">
                <div class="logo">
                    <i class="fas fa-plus-circle"></i> Dodaj Produkt
                </div>
                <div class="nav-links">
                    <a href="../shop/index.php" class="nav-link"><i class="fas fa-store"></i> Sklep</a>
                    <a href="../shop/cart.php" class="nav-link"><i class="fas fa-shopping-cart"></i> Koszyk</a>
                </div>
            </nav>
        </header>

        <div class="cart-container">
            <h2 style="text-align: center; margin-bottom: 2rem; background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                Dodaj nowy produkt
            </h2>

            <?php if (isset($success)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data" style="max-width: 600px; margin: 0 auto;">
                <div style="display: grid; gap: 1.5rem;">
                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Nazwa produktu *</label>
                        <input type="text" name="name" required 
                               style="width: 100%; padding: 1rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;"
                               placeholder="Nazwa produktu">
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Opis produktu</label>
                        <textarea name="description" rows="4" 
                                  style="width: 100%; padding: 1rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;"
                                  placeholder="Opis produktu"></textarea>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Cena (zł) *</label>
                            <input type="number" name="price" step="0.01" min="0" required 
                                   style="width: 100%; padding: 1rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;"
                                   placeholder="0.00">
                        </div>

                        <div>
                            <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Kategoria *</label>
                            <select name="category" required 
                                    style="width: 100%; padding: 1rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;">
                                <option value="">Wybierz kategorię</option>
                                <option value="Telefony">Telefony</option>
                                <option value="Laptopy">Laptopy</option>
                                <option value="Tablety">Tablety</option>
                                <option value="Gaming">Gaming</option>
                                <option value="Audio">Audio</option>
                                <option value="Akcesoria">Akcesoria</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label style="display: block; margin-bottom: 0.5rem; font-weight: 600;">Zdjęcie produktu</label>
                        <input type="file" name="image" accept="image/*"
                               style="width: 100%; padding: 1rem; border: 2px solid #e9ecef; border-radius: 10px; font-size: 1rem;">
                    </div>

                    <button type="submit" class="btn btn-success" style="font-size: 1.1rem; padding: 1rem 2rem; margin-top: 1rem;">
                        <i class="fas fa-plus-circle"></i> Dodaj produkt
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>