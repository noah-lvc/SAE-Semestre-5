<?php
include_once "../templates/header.html";

echo "
<title>Création Utilisateur</title>
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

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_submit'])) {
    $login = trim($_POST['login']);
    $mdp = trim($_POST['mdp']);
    $mdpConfirm = trim($_POST['mdp_confirm']);

    if (strlen($login) < 4) {
        $message = "<p style='color:red; text-align:center;'>Le login doit contenir au moins 4 caractères.</p>";
    } elseif (strlen($mdp) < 6) {
        $message = "<p style='color:red; text-align:center;'>Le mot de passe doit contenir au moins 6 caractères.</p>";
    } elseif ($mdp !== $mdpConfirm) {
        $message = "<p style='color:red; text-align:center;'>Les mots de passe ne correspondent pas.</p>";
    } else {
        $stmtCheck = $pdo->prepare("SELECT id FROM user WHERE login = ?");
        $stmtCheck->execute([$login]);
        if ($stmtCheck->fetch()) {
            $message = "<p style='color:red; text-align:center;'>Ce login existe déjà.</p>";
        } else {
            $pdo->beginTransaction();
            try {
                $stmtInsertUser = $pdo->prepare("INSERT INTO user (login) VALUES (?)");
                $stmtInsertUser->execute([$login]);
                $userId = $pdo->lastInsertId();

                $stmtInsertPwd = $pdo->prepare("INSERT INTO password (user_id, password) VALUES (?, ?)");
                $stmtInsertPwd->execute([$userId, $mdp]);

                $stmtLog = $pdo->prepare("INSERT INTO logs (ip_address, login, date, action) VALUES (?, ?, NOW(), ?)");
                $stmtLog->execute([$_SERVER['REMOTE_ADDR'], $login, 'création_utilisateur']);

                $pdo->commit();
                $message = "<p style='color:green; text-align:center;'>Utilisateur $login ajouté avec succès.</p>";
            } catch (Exception $e) {
                $pdo->rollBack();
                $message = "<p style='color:red; text-align:center;'>Erreur lors de l'ajout de l'utilisateur.</p>";
            }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fichier_csv'])) {
    $file = $_FILES['fichier_csv'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        $handle = fopen($file['tmp_name'], "r");
        if ($handle !== false) {
            $addedCount = 0;
            while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                if (count($data) < 2) continue;
                $login = trim($data[0]);
                $mdp = trim($data[1]);

                if (strlen($login) < 4 || strlen($mdp) < 6) continue;

                $stmtCheck = $pdo->prepare("SELECT id FROM user WHERE login = ?");
                $stmtCheck->execute([$login]);
                if ($stmtCheck->fetch()) continue;

                $pdo->beginTransaction();
                try {
                    $stmtInsertUser = $pdo->prepare("INSERT INTO user (login) VALUES (?)");
                    $stmtInsertUser->execute([$login]);
                    $userId = $pdo->lastInsertId();

                    $stmtInsertPwd = $pdo->prepare("INSERT INTO password (user_id, password) VALUES (?, ?)");
                    $stmtInsertPwd->execute([$userId, $mdp]);

                    $stmtLog = $pdo->prepare("INSERT INTO logs (ip_address, login, date, action) VALUES (?, ?, NOW(), ?)");
                    $stmtLog->execute([$_SERVER['REMOTE_ADDR'], $login, 'création_utilisateur_csv']);

                    $pdo->commit();
                    $addedCount++;
                } catch (Exception $e) {
                    $pdo->rollBack();
                }
            }
            fclose($handle);
            $message = "<p style='color:green; text-align:center;'>Import terminé : $addedCount utilisateur(s) ajouté(s).</p>";
        } else {
            $message = "<p style='color:red; text-align:center;'>Impossible d'ouvrir le fichier CSV.</p>";
        }
    } else {
        $message = "<p style='color:red; text-align:center;'>Erreur lors de l'import du fichier CSV.</p>";
    }
}

echo "
<main class='creation-container'>
    <section class='formulaire-inscription'>
        <h2>Créer un utilisateur</h2>
        <form method='POST'>
            <label for='login'>Login</label><br>
            <input type='text' id='login' name='login' placeholder='Entrez un login' minlength='4' required><br>

            <label for='mdp'>Mot de passe</label><br>
            <input type='password' id='mdp' name='mdp' placeholder='Mot de passe' minlength='6' required><br>

            <label for='mdp-confirm'>Confirmer le mot de passe</label><br>
            <input type='password' id='mdp-confirm' name='mdp_confirm' placeholder='Confirmez le mot de passe' minlength='6' required><br>

            <button type='submit' name='form_submit'>Créer</button>
        </form>
    </section>

    <section class='import-csv'>
        <h2>Ajouter via CSV</h2>
        <form method='POST' enctype='multipart/form-data'>
            <label for='fichier-csv'>Importer un fichier CSV (login,password) :</label><br>
            <input type='file' id='fichier-csv' name='fichier_csv' accept='.csv' required><br>
            <button type='submit'>Importer</button>
        </form>
    </section>
</main>
";

echo $message;

include_once "../templates/footer.html";