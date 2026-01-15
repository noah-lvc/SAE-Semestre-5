<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['login'])) {
    header("Location: ../pages/accueil.php");
    exit();
}

$host = 'localhost';
$dbname = 'bd_cluster';
$user = 'sae5';
$pass = 'sae5';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

$stmt = $pdo->prepare("SELECT profil_picture FROM user WHERE login = ?");
$stmt->execute([$_SESSION['login']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$profil_picture = $user['profil_picture'] ?? null;

$avatarSrc = $profil_picture ? "../images/pfp/$profil_picture" : "../images/default_avatar.png";
?>

<div class="barnav">
    <a href="../pages/accueil.php">
        <img src="../images/ClusterLabLogo.png" alt="Logo du site" class="logo">
    </a>
    <h1>ClusterLab</h1>

    <div class="barnav-right">
        <div class="menu-burger" onclick="toggleMenu()">
            <div class="bar"></div>
            <div class="bar"></div>
            <div class="bar"></div>
        </div>

        <?php if ($profil_picture): ?>
            <?php $timestamp = time(); ?>
            <img src='../images/pfp/<?php echo $profil_picture . "?$timestamp"; ?>' alt='Avatar utilisateur' class='avatar'>
        <?php else: ?>
            <?php $firstLetter = strtoupper($_SESSION['login'][0]); ?>
            <div class="avatar-text"><?php echo $firstLetter; ?></div>
        <?php endif; ?>
    </div>

    <nav class="menu-deroulant" id="menu-deroulant">
        <ul>
            <li><a href="../pages/profil.php">Profil</a></li>
            <li><a href="../pages/prime.php">Calcul prime</a></li>
            <li><a href="../pages/monteCarlo.php">Calcul monte carlo</a></li>
            <li><a href="../pages/calcIntegral.php">Calcul intégral</a></li>
        </ul>
    </nav>
</div>

<script src="../javascript/javascript.js"></script>