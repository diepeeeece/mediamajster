<?php
session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koszyk - TechShop</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container">
        <header class="header">
            <nav class="navbar">
                <div class="logo">
                    <i class="fas fa-shopping-cart"></i> Twój Koszyk
                </div>
                <div class="nav-links">
                    <a href="index.php" class="nav-link"><i class="fas fa-arrow-left"></i> Kontynuuj zakupy</a>
                </div>
            </nav>
        </header>

        <div class="cart-container">
            <?php if (!empty($_SESSION['koszyk']) && is_array($_SESSION['koszyk'])): ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produkt</th>
                            <th>Cena</th>
                            <th>Ilość</th>
                            <th>Suma</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $total = 0;
                        foreach ($_SESSION['koszyk'] as $index => $item):
                            if (is_array($item) && isset($item['price']) && isset($item['ilosc'])):
                                $sum = floatval($item['price']) * intval($item['ilosc']);
                                $total += $sum;
                        ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 1rem;">
                                    <?php if (isset($item['image'])): ?>
                                    <img src="../assets/images/<?php echo $item['image']; ?>" 
                                         alt="<?php echo $item['name']; ?>" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 10px;">
                                    <?php endif; ?>
                                    <div>
                                        <strong><?php echo htmlspecialchars($item['name'] ?? 'Produkt'); ?></strong>
                                    </div>
                                </div>
                            </td>
                            <td><?php echo number_format($item['price'] ?? 0, 2); ?> zł</td>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <span style="font-weight: bold; font-size: 1.1rem;"><?php echo $item['ilosc'] ?? 0; ?></span>
                                </div>
                            </td>
                            <td style="font-weight: bold; color: #27ae60;">
                                <?php echo number_format($sum, 2); ?> zł
                            </td>
                            <td>
                                <form action="remove_item.php" method="POST" style="display: inline;">
                                    <input type="hidden" name="index" value="<?php echo $index; ?>">
                                    <button type="submit" class="btn btn-danger" style="padding: 0.5rem 1rem;">
                                        <i class="fas fa-trash"></i> Usuń
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </tbody>
                </table>

                <div class="total-price">
                    <div style="font-size: 1.2rem; color: #7f8c8d; margin-bottom: 0.5rem;">Suma całkowita:</div>
                    <div style="font-size: 2.5rem; font-weight: bold; background: linear-gradient(45deg, #667eea, #764ba2); -webkit-background-clip: text; -webkit-text-fill-color: transparent;">
                        <?php echo number_format($total, 2); ?> zł
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; justify-content: center; margin-top: 2rem;">
                    <form action="clear_cart.php" method="POST" style="display: inline;">
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-broom"></i> Wyczyść koszyk
                        </button>
                    </form>
                    <button class="btn btn-success" style="font-size: 1.1rem; padding: 1rem 2rem;">
                        <i class="fas fa-credit-card"></i> Przejdź do płatności
                    </button>
                </div>

            <?php else: ?>
                <div class="empty-cart">
                    <i class="fas fa-shopping-cart" style="font-size: 5rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                    <h2>Twój koszyk jest pusty</h2>
                    <p style="margin-bottom: 2rem; color: #7f8c8d;">Dodaj jakieś produkty, aby je zobaczyć tutaj</p>
                    <a href="index.php" class="btn btn-primary" style="font-size: 1.1rem; padding: 1rem 2rem;">
                        <i class="fas fa-shopping-bag"></i> Przejdź do sklepu
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>