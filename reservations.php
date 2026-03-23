<?php
require 'config/db.php';

$reservations = $pdo->query("
    SELECT r.id, u.nom, u.prenom, u.email, r.destination, r.date_depart, r.date_retour
    FROM reservations r
    JOIN users u ON r.user_id = u.id
")->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes réservations – Guez’Trip</title>
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
    <h2>📋 Liste des réservations</h2>
    <p>Gérez toutes les réservations enregistrées</p>
</section>

<section class="reservations-container">
    <table class="reservations-table">
        <thead>
            <tr>
                <th>Voyageur</th>
                <th>Email</th>
                <th>Destination</th>
                <th>Dates</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reservations as $r): ?>
            <tr>
                <td><?= htmlspecialchars("$r[prenom] $r[nom]") ?></td>
                <td><?= htmlspecialchars($r['email']) ?></td>
                <td><?= htmlspecialchars($r['destination']) ?></td>
                <td><?= $r['date_depart']." → ".$r['date_retour'] ?></td>
                <td>
                    <a href="edit_reservation.php?id=<?= $r['id'] ?>" class="btn-edit">Modifier</a>
                    <a href="delete_reservation.php?id=<?= $r['id'] ?>" onclick="return confirm('Voulez-vous vraiment supprimer ?')" class="btn-delete">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</section>

</body>
</html>
