<?php
include_once "../templates/header.html";

echo "
<title>Profil</title>
</head>
<body>";

include_once "../gestion/fonctions.php";
afficherBarnav();

echo "
<div class='profil-container'>
    <div class='pfp' style='text-align:center;'>
        <img src='../images/logo.jpg' alt='Logo du site' class='logo'>
    </div>
    
    <h2 style='margin-top:10px; margin-bottom: 60px;'>" . strtoupper(htmlspecialchars($_SESSION['login'])) . "</h2>

    <form class='profil-form' method='post'>
        <input type='password' name='AncienMDP' placeholder='Ancien mot de passe' minlength='6' required>
        <input type='password' name='NouveauMDP' placeholder='Nouveau mot de passe' minlength='6' required>
        <input type='password' name='ConfirmerMdp' placeholder='Réécrire nouveau mot de passe' minlength='6' required>
        <button type='submit' name='ModifierMDP'>Confirmer</button>
    </form>
    
    <form class='profil-actions' method='post'>
        <button type='submit' name='Deconnexion' class='logout-btn'>Déconnexion</button>
        <button type='submit' name='SupprimerCompte' class='delete-btn' onclick='return confirm(\"Voulez-vous vraiment supprimer votre compte ? Cette action est irréversible.\");'>Supprimer compte</button>
    </form>
</div>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Deconnexion'])) {
    if (isset($_SESSION['login'])) {
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

        $insertLog = $pdo->prepare("INSERT INTO logs (ip_address, login, date, action) VALUES (?, ?, NOW(), ?)");
        $insertLog->execute([$_SERVER['REMOTE_ADDR'], $_SESSION['login'], 'deconnexion']);
    }
    session_unset();
    session_destroy();
    header("Location: ../pages/accueil.php");
    exit();
}

if (!isset($_SESSION['profil']) || !isset($_SESSION['login'])) {
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['SupprimerCompte'])) {
    $login = $_SESSION['login'];

    $stmt = $pdo->prepare("SELECT id FROM user WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $userId = $user['id'];

        $deletePass = $pdo->prepare("DELETE FROM password WHERE user_id = ?");
        $deletePass->execute([$userId]);

        $deleteUser = $pdo->prepare("DELETE FROM user WHERE id = ?");
        $deleteUser->execute([$userId]);

        $insertLog = $pdo->prepare("INSERT INTO logs (ip_address, login, date, action) VALUES (?, ?, NOW(), ?)");
        $insertLog->execute([$_SERVER['REMOTE_ADDR'], $login, 'suppression_compte']);

        session_unset();
        session_destroy();
        header("Location: ../pages/accueil.php");
        exit();
    } else {
        echo "<p style='color:red; text-align:center; margin-top:20px;'>Erreur : utilisateur introuvable.</p>";
    }
}

include_once "../templates/footer.html";