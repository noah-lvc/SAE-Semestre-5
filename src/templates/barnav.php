<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
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

$messageInscription = '';
$messageConnexion = '';
$modalToOpen = '';

$elem1 = rand(1, 10);
$elem2 = rand(1, 10);
$valeur_captcha = $elem1 * $elem2;
setcookie('captcha', $valeur_captcha, time() + 1800, "/");

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login-inscription'], $_POST['mdp-inscription'], $_POST['mdp-inscription-confirmation'], $_POST['captcha'])) {
    $login = trim($_POST['login-inscription']);
    $mdp = trim($_POST['mdp-inscription']);
    $mdp_confirm = trim($_POST['mdp-inscription-confirmation']);
    $captcha = trim($_POST['captcha']);

    if ($mdp !== $mdp_confirm) {
        $messageInscription = "Les mots de passe ne correspondent pas.";
    } elseif (strlen($login) < 4) {
        $messageInscription = "Le login doit contenir au moins 4 caractères.";
    } elseif (strlen($mdp) < 6) {
        $messageInscription = "Le mot de passe doit contenir au moins 6 caractères.";
    } elseif (!isset($_COOKIE['captcha']) || $captcha != $_COOKIE['captcha']) {
        $messageInscription = "Captcha incorrect. Veuillez réessayer.";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM user WHERE login = ?");
        $stmt->execute([$login]);

        if ($stmt->rowCount() > 0) {
            $messageInscription = "Ce login existe déjà.";
        } else {
            $insertUser = $pdo->prepare("INSERT INTO user (login) VALUES (?)");
            $insertUser->execute([$login]);
            $userId = $pdo->lastInsertId();

            $insertPass = $pdo->prepare("INSERT INTO password (user_id, password) VALUES (?, ?)");
            $insertPass->execute([$userId, $mdp]);

            $insertLog = $pdo->prepare("INSERT INTO logs (ip_address, login, date, action) VALUES (?, ?, NOW(), ?)");
            $insertLog->execute([$_SERVER['REMOTE_ADDR'], $login, 'inscription']);

            $_SESSION['login'] = $login;
            $_SESSION['profil'] = 'connected';

            header("Location: ../pages/accueil.php");
            exit();
        }
    }

    if ($messageInscription) $modalToOpen = 'inscription';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login-connexion'], $_POST['mdp-connexion'])) {
    $login = trim($_POST['login-connexion']);
    $mdp = trim($_POST['mdp-connexion']);

    if ($login === 'adminweb') {
        if ($mdp === 'adminweb') {
            $_SESSION['login'] = $login;
            $_SESSION['profil'] = 'adminweb';

            $insertLog = $pdo->prepare("INSERT INTO logs (ip_address, login, date, action) VALUES (?, ?, NOW(), ?)");
            $insertLog->execute([$_SERVER['REMOTE_ADDR'], $login, 'connexion']);

            header("Location: ../pages/accueil.php");
            exit();
        } else {
            $messageConnexion = "Mot de passe incorrect.";
            $modalToOpen = 'connexion';
        }
    } elseif ($login === 'adminsys') {
        if ($mdp === 'adminsys') {
            $_SESSION['login'] = $login;
            $_SESSION['profil'] = 'adminsys';

            $insertLog = $pdo->prepare("INSERT INTO logs (ip_address, login, date, action) VALUES (?, ?, NOW(), ?)");
            $insertLog->execute([$_SERVER['REMOTE_ADDR'], $login, 'connexion']);

            header("Location: ../pages/accueil.php");
            exit();
        } else {
            $messageConnexion = "Mot de passe incorrect.";
            $modalToOpen = 'connexion';
        }
    } else {
        $stmt = $pdo->prepare("SELECT id FROM user WHERE login = ?");
        $stmt->execute([$login]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $messageConnexion = "Login inexistant.";
            $modalToOpen = 'connexion';
        } else {
            $stmt = $pdo->prepare("SELECT password FROM password WHERE user_id = ?");
            $stmt->execute([$user['id']]);
            $passRow = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$passRow || $mdp !== $passRow['password']) {
                $messageConnexion = "Mot de passe incorrect.";
                $modalToOpen = 'connexion';
            } else {
                $_SESSION['login'] = $login;
                $_SESSION['profil'] = 'connected';

                $insertLog = $pdo->prepare("INSERT INTO logs (ip_address, login, date, action) VALUES (?, ?, NOW(), ?)");
                $insertLog->execute([$_SERVER['REMOTE_ADDR'], $login, 'connexion']);

                header("Location: ../pages/accueil.php");
                exit();
            }
        }
    }
}

echo "
<div class='barnav'>
    <a href='../pages/accueil.php'><img src='../images/ClusterLabLogo.png' alt='Logo du site' class='logo'></a>
    <h1>ClusterLab</h1>
    <nav>
        <ol>
            <li><button type='button' class='button_barnav' onclick='openModal(\"inscription\")'>Inscription</button></li>
            <li><button type='button' class='button_barnav' onclick='openModal(\"connexion\")'>Connexion</button></li>
        </ol>
    </nav>
</div>

<script src='../javascript/javascript.js'></script>

<div id='inscription' class='modal'>
    <div class='modal-content'>
        <span class='close' onclick='closeModal(\"inscription\")'>&times;</span>
        <h2>Inscription</h2>
        <form method='POST'>
            <label for='login-inscription'>Login</label><br>
            <input type='text' id='login-inscription' name='login-inscription' placeholder='Login' minlength='4' required><br>

            <label for='mdp-inscription'>Mot de passe</label><br>
            <input type='password' id='mdp-inscription' name='mdp-inscription' placeholder='Mot de passe' minlength='6' required><br>

            <label for='mdp-inscription-confirmation'>Confirmer le mot de passe</label><br>
            <input type='password' id='mdp-inscription-confirmation' name='mdp-inscription-confirmation' placeholder='Mot de passe' minlength='6' required><br>

            <label for='captcha'>$elem1 * $elem2 = ?</label><br>
            <input type='text' id='captcha' name='captcha' placeholder='Résultat de l’opération' required><br>

            <p>J'ai déjà un compte ? <a href='#' onclick='switchModal(\"inscription\",\"connexion\")'>Connectez-vous</a>.</p>
            <button type='submit'>S'inscrire</button>";

if ($messageInscription) {
    echo "<p style='color:red; text-align:center; margin-top:10px;'>".htmlspecialchars($messageInscription)."</p>";
}

echo "
        </form>
    </div>
</div>

<div id='connexion' class='modal'>
    <div class='modal-content'>
        <span class='close' onclick='closeModal(\"connexion\")'>&times;</span>
        <h2>Connexion</h2>
        <form method='POST'>
            <label for='login-connexion'>Login</label><br>
            <input type='text' id='login-connexion' name='login-connexion' placeholder='Login' required><br>
            <label for='mdp-connexion'>Mot de passe</label><br>
            <input type='password' id='mdp-connexion' name='mdp-connexion' placeholder='Mot de passe' required><br>
            <p>Pas encore de compte ? <a href='#' onclick='switchModal(\"connexion\",\"inscription\")'>Créez-en un</a>.</p>
            <button type='submit'>Se connecter</button>";

if ($messageConnexion) {
    echo "<p style='color:red; text-align:center; margin-top:10px;'>".htmlspecialchars($messageConnexion)."</p>";
}

echo "
        </form>
    </div>
</div>
";

if ($modalToOpen) {
    echo "
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            openModal('$modalToOpen');
        });
    </script>";
}