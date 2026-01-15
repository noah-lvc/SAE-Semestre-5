<?php
session_start();
include_once "../templates/header.html";
echo "
<title>Gérer les Comptes</title>
<style>
    table {
        width: 70%;
        border-collapse: collapse;
        margin: 50px auto;
        margin-bottom: 200px;
    }

    th {
        border: 1px solid #ddd;
        font-size: 20px;
        padding: 30px;
        text-align: center;
        vertical-align: middle;
        background-color: #1e1e2f;
        color: white;
        font-weight: bold;
    }
    
    td {
        border: 1px solid #ddd;
        padding: 20px;
        text-align: center;
        vertical-align: middle;
        font-size: 18px;
    }

    tr:hover {
        background-color: #ddd;
    }

    .avatar-img {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        display: block;
        margin: auto;
    }

    .avatar-text {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: #70758A;
        color: white;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
    }

    .delete-link {
        color: red;
        text-decoration: none;
        font-weight: bold;
        cursor: pointer;
    }

    .delete-link.disabled {
        font-size: 18px;
        color: #1e1e2f;
        pointer-events: none;
        cursor: default;
        text-decoration: none;
    }
</style>
</head>
<body>
";
include_once "../gestion/fonctions.php";
afficherBarnav();

echo "
<h1 style='text-align:center; color:#1e1e2f; margin-top:100px;'>Liste des utilisateurs</h1>
";

$host = 'localhost';
$dbname = 'bd_cluster';
$user = 'sae5';
$pass = 'sae5';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (isset($_GET['delete'])) {
    $loginToDelete = $_GET['delete'];
    if ($loginToDelete != "adminweb" && $loginToDelete != "adminsys") {
        $stmt = $pdo->prepare("SELECT id FROM user WHERE login = ?");
        $stmt->execute([$loginToDelete]);
        $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($userRow) {
            $userId = $userRow['id'];
            $pdo->beginTransaction();
            try {
                $stmtDeletePwd = $pdo->prepare("DELETE FROM password WHERE user_id = ?");
                $stmtDeletePwd->execute([$userId]);
                $stmtDeleteUser = $pdo->prepare("DELETE FROM user WHERE id = ?");
                $stmtDeleteUser->execute([$userId]);
                $stmtLog = $pdo->prepare("INSERT INTO logs (ip_address, login, date, action) VALUES (?, ?, NOW(), ?)");
                $stmtLog->execute([$_SERVER['REMOTE_ADDR'], $loginToDelete, 'suppression_utilisateur']);
                $pdo->commit();
                echo "<p style='color:green; text-align:center;'>Utilisateur $loginToDelete supprimé avec succès.</p>";
            } catch (Exception $e) {
                $pdo->rollBack();
                echo "<p style='color:red; text-align:center;'>Erreur lors de la suppression.</p>";
            }
        } else {
            echo "<p style='color:red; text-align:center;'>Utilisateur introuvable.</p>";
        }
    } else {
        echo "<p style='color:red; text-align:center;'>Impossible de supprimer cet utilisateur.</p>";
    }
}

$stmt = $pdo->query("SELECT * FROM user");
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($users) {
    echo "<table>";
    echo "<tr>";
    foreach (array_keys($users[0]) as $colName) {
        echo "<th>$colName</th>";
    }
    echo "<th>Supprimer</th>";
    echo "</tr>";

    foreach ($users as $user) {
        echo "<tr>";
        foreach ($user as $key => $value) {
            if ($key == 'profil_picture') {
                if ($value) {
                    echo "<td><img src='../images/pfp/$value' class='avatar-img' alt='avatar'></td>";
                } else {
                    $firstLetter = strtoupper($user['login'][0]);
                    echo "<td><div class='avatar-text'>$firstLetter</div></td>";
                }
            } else {
                echo "<td>" . htmlspecialchars($value) . "</td>";
            }
        }

        if ($user['login'] != "adminweb" && $user['login'] != "adminsys") {
            echo "<td><a href='?delete=" . $user['login'] . "' class='delete-link' onclick='return confirm(\"Voulez-vous vraiment supprimer cet utilisateur ?\");'>Supprimer</a></td>";
        } else {
            echo "<td><span class='delete-link disabled'>Non supprimable</span></td>";
        }

        echo "</tr>";
    }

    echo "</table>";
} else {
    echo "<p style='text-align:center;'>Aucun utilisateur trouvé.</p>";
}

include_once "../templates/footer.html";