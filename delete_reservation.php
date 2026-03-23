<?php
require 'config/db.php';

if (isset($_GET['id'])) {
    $stmt = $pdo->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->execute([$_GET['id']]);
}

header("Location: reservations.php");
exit;
