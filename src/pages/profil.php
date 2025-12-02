<?php
include_once "../templates/header.html";
echo "
<title>Profil</title>
<style>
    .profil-actions .delete-btn {
        color: #1e1e2f;
    }
    .form-supprimer-pfp .supprimer-pfp-button {
        margin-bottom: 30px;
        background-color: lightgrey;
        border-color: #1e1e2f;
        color: #1e1e2f;
        font-size: 12px;
        font-weight: bold;
        padding: 8px 15px;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s;
    }
    
    .form-supprimer-pfp .supprimer-pfp-button:hover {
        background-color: darkgrey;
    }
</style>
</head>
<body>
";
include_once "../gestion/fonctions.php";
afficherBarnav();

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

if (!isset($_SESSION['profil']) || !isset($_SESSION['login'])) {
    header("Location: ../pages/accueil.php");
    exit();
}

$stmt = $pdo->prepare("SELECT profil_picture FROM user WHERE login = ?");
$stmt->execute([$_SESSION['login']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
$profil_picture = $user['profil_picture'] ?? null;

$uploadMessage = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['newPfp'])) {
    $file = $_FILES['newPfp'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $allowed = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($file['type'], $allowed)) {
            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $newName = 'pfp_'.$_SESSION['login'].'.'.$ext;
            $uploadDir = '../images/pfp/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $destination = $uploadDir . $newName;
            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $stmt = $pdo->prepare("UPDATE user SET profil_picture = ? WHERE login = ?");
                $stmt->execute([$newName, $_SESSION['login']]);
                header("Location: profil.php");
                exit();
            } else {
                $uploadMessage = "Erreur lors de l'upload.";
            }
        } else {
            $uploadMessage = "Type de fichier non autorisé. Seules les images jpg, png, gif sont acceptées.";
        }
    } else {
        $uploadMessage = "Erreur de fichier uploadé.";
    }
}

echo "
<div class='profil-container' style='text-align:center;'>

    <form method='POST' enctype='multipart/form-data' style='display:inline-block;'>
        <label for='newPfp' style='cursor:pointer; display:block;'>
            <div class='pfp' style='width:150px; height:150px; border-radius:50%; overflow:hidden; margin:0 auto; display:flex; align-items:center; justify-content:center; background:#ddd;'>
";

if ($profil_picture) {
    $timestamp = time();
    echo "<img src='../images/pfp/$profil_picture?$timestamp' alt='Photo de profil' style='width:100%; height:100%; object-fit:cover;'>";
} else {
    $firstLetter = strtoupper($_SESSION['login'][0]);
    echo "<div class='avatar-text' style='width:100%; height:100%; font-size:60px;'>$firstLetter</div>";
}

echo "
            </div>
        </label>
        <input type='file' id='newPfp' name='newPfp' style='display:none;' onchange='this.form.submit()'>
    </form>
    
    <form method='POST' class='form-supprimer-pfp' style='margin-top:10px;'>
        <button type='submit' name='SupprimerPfp' class='supprimer-pfp-button' onclick='return confirm(\"Voulez-vous vraiment supprimer votre photo de profil ?\");'>Supprimer la photo de profil</button>
    </form>


    <h2 style='margin-top:10px; margin-bottom: 60px;'>" . strtoupper(htmlspecialchars($_SESSION['login'])) . "</h2>

    <form class='profil-form' method='post'>
        <label for='AncienMDP'>Ancien mot de passe</label>
        <input type='password' id='AncienMDP' name='AncienMDP' placeholder='Ancien mot de passe' minlength='6' required>
        <label for='NouveauMDP'>Nouveau mot de passe</label>
        <input type='password' id='NouveauMDP' name='NouveauMDP' placeholder='Nouveau mot de passe' minlength='6' required>
        <label for='ConfirmerMDP'>Confirmer le nouveau mot de passe</label>
        <input type='password' id='ConfirmerMDP' name='ConfirmerMdp' placeholder='Réécrire nouveau mot de passe' minlength='6' required>
        <button type='submit' name='ModifierMDP'>Confirmer</button>
    </form>
    
    <form class='profil-actions' method='post'>
        <button type='submit' name='Deconnexion' class='logout-btn'>Déconnexion</button>
        <button type='submit' name='SupprimerCompte' class='delete-btn' onclick='return confirm(\"Voulez-vous vraiment supprimer votre compte ? Cette action est irréversible.\");'>Supprimer compte</button>
    </form>
";

if ($uploadMessage) {
    echo "<p style='color:green; margin-top:15px;'>$uploadMessage</p>";
}

echo "</div>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Deconnexion'])) {
    if (isset($_SESSION['login'])) {
        $insertLog = $pdo->prepare("INSERT INTO logs (ip_address, login, date, action) VALUES (?, ?, NOW(), ?)");
        $insertLog->execute([$_SERVER['REMOTE_ADDR'], $_SESSION['login'], 'deconnexion']);
    }
    session_unset();
    session_destroy();
    header("Location: ../pages/accueil.php");
    exit();
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['SupprimerPfp'])) {
    $login = $_SESSION['login'];

    $stmt = $pdo->prepare("SELECT profil_picture FROM user WHERE login = ?");
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user && $user['profil_picture']) {
        $filePath = '../images/pfp/' . $user['profil_picture'];
        if (file_exists($filePath)) unlink($filePath);
    }

    $stmt = $pdo->prepare("UPDATE user SET profil_picture = NULL WHERE login = ?");
    $stmt->execute([$login]);

    $profil_picture = null;

    header("Location: profil.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ModifierMDP'])) {
    $login = $_SESSION['login'];
    $ancienMDP = $_POST['AncienMDP'] ?? '';
    $nouveauMDP = $_POST['NouveauMDP'] ?? '';
    $confirmerMDP = $_POST['ConfirmerMdp'] ?? '';

    if ($nouveauMDP !== $confirmerMDP) {
        echo "<p style='color:red; text-align:center; margin-top:10px;'>Les nouveaux mots de passe ne correspondent pas.</p>";
    } else {
        $stmt = $pdo->prepare("SELECT password FROM password WHERE user_id = (SELECT id FROM user WHERE login = ?)");
        $stmt->execute([$login]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row || $ancienMDP !== $row['password']) {
            echo "<p style='color:red; text-align:center; margin-top:10px;'>Ancien mot de passe incorrect.</p>";
        } else {
            $stmt = $pdo->prepare("UPDATE password SET password = ? WHERE user_id = (SELECT id FROM user WHERE login = ?)");
            $stmt->execute([$nouveauMDP, $login]);

            echo "<p style='color:green; text-align:center; margin-top:10px;'>Mot de passe mis à jour avec succès.</p>";
        }
    }
}

include_once "../templates/footer.html";