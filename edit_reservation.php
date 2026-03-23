<?php
require 'config/db.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: reservations.php");
    exit;
}

// Préparer la requête
$stmt = $pdo->prepare("
    SELECT r.*, u.nom, u.prenom, u.email
    FROM reservations r
    JOIN users u ON r.user_id = u.id
    WHERE r.id = ?
");

// Exécuter avec l'ID
$stmt->execute([$id]);

// Récupérer les données
$data = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$data) {
    header("Location: reservations.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $stmt = $pdo->prepare("
        UPDATE reservations 
        SET destination = ?, date_depart = ?, date_retour = ?
        WHERE id = ?
    ");
    $stmt->execute([
        $_POST['destination'],
        $_POST['date_depart'],
        $_POST['date_retour'],
        $id
    ]);
    header("Location: reservations.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier réservation – Guez’Trip</title>
    <link rel="stylesheet" href="style-voyage.css">
</head>
<body>

<header class="hero small-hero">
    <nav class="navbar">
        <div class="logo">
            
            <div>
                <h1>Guez’Trip</h1>
                <span>Pour un séjour pété</span>
            </div>
        </div>
        <div class="nav-actions">
            <a href="projet_voyage.php" class="nav-link">Accueil</a>
            <a href="reservations.php" class="nav-link">Réservations</a>
        </div>
    </nav>
</header>

<section class="page-title">
    <h2>✏️ Modifier la réservation</h2>
    <p><?= htmlspecialchars("$data[prenom] $data[nom]") ?> – <?= htmlspecialchars($data['email']) ?></p>
</section>

<section class="form-box">
    <form method="post">
        <label>Destination</label>
        <input type="text" name="destination" value="<?= htmlspecialchars($data['destination']) ?>" required>

        <label>Date de départ</label>
        <input type="date" name="date_depart" value="<?= $data['date_depart'] ?>" required>

        <label>Date de retour</label>
        <input type="date" name="date_retour" value="<?= $data['date_retour'] ?>" required>

        <button type="submit">💾 Enregistrer les modifications</button>
    </form>
</section>

</body>
</html>
