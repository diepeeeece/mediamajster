<?php
session_start();

if (isset($_POST['index']) && is_numeric($_POST['index'])) {
    $index = intval($_POST['index']);
    if (isset($_SESSION['koszyk'][$index])) {
        unset($_SESSION['koszyk'][$index]);
        $_SESSION['koszyk'] = array_values($_SESSION['koszyk']);
    }
}

header("Location: cart.php");
exit;
?>