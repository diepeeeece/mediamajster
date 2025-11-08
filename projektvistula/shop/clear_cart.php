<?php
session_start();
$_SESSION['koszyk'] = [];
header("Location: cart.php");
exit;
?>