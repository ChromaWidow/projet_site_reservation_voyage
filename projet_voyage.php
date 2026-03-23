<?php
require 'config/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $destination = trim($_POST['destination']);
    $date_depart = $_POST['date_depart'];
    $date_retour = $_POST['date_retour'];

    
    if ($nom && $prenom && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        
        $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email]);
        $user_id = $pdo->lastInsertId();

        
        $stmt = $pdo->prepare("
            INSERT INTO reservations (user_id, destination, date_depart, date_retour)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$user_id, $destination, $date_depart, $date_retour]);

        $message = "✅ Réservation effectuée avec succès !";
    } else {
        $message = "❌ Veuillez remplir correctement tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Guez'Trip</title>
    <link rel="stylesheet" href="style-voyage.css">
</head>
<body>

<header class="hero">
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

<section class="intro">
    <h2>Voyagez n’importe où, mais avec style</h2>
    <p>Chez <strong>Guez’Trip</strong>, on vous envoie à <em>Pétaouchnok</em>, mais toujours au meilleur prix.</p>
</section>

<?php if ($message): ?>
<div class="alert"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<section class="destinations">
    <h2>Nos destinations</h2>
    <div class="cards-grid">
        <?php 
        $destinations = [
            ["Mouais", 4500, "images/Mouais.jfif"],
            ["LaTrique", 5200, "images/la trique.jfif"],
            ["Bourré", 9800, "images/bourre.jpg"],
            ["Vatan", 4900, "images/vatan.jpg"],
            ["Le Fion", 11000, "images/lefion.jpg"],
            ["Bezons", 12000, "images/bezons.jpg"],
            ["Cité de FroidCul", 4300, "images/froidcul.jfif"],
            ["Belbése", 3900, "images/belbeze.jpg"]
        ];

        foreach ($destinations as $d): ?>
        <div class="card">
            <img src="<?= $d[2] ?>" alt="<?= $d[0] ?>">
            <h3><?= $d[0] ?></h3>
            <p class="price"><?= $d[1] ?> €</p>
            <button onclick="selectDestination('<?= $d[0] ?>')">Réserver</button>
        </div>
        <?php endforeach; ?>
    </div>
</section>

<section class="reservation">
    <h2>Finaliser la réservation</h2>
    <form method="post" class="reservation-form">
        <div class="row">
            <input type="text" name="nom" placeholder="Nom" required>
            <input type="text" name="prenom" placeholder="Prénom" required>
        </div>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" id="destination" name="destination" placeholder="Destination" readonly required>
        <div class="row">
            <input type="date" name="date_depart" required>
            <input type="date" name="date_retour" required>
        </div>
        <button type="submit">Confirmer la réservation</button>
    </form>
</section>

<script>
function selectDestination(dest) {
    document.getElementById("destination").value = dest;
    document.querySelector(".reservation").scrollIntoView({behavior:"smooth"});
}
</script>

</body>
</html>
