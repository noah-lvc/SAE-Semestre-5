<?php
session_start();

$cnx = mysqli_connect("localhost", "sae5", "sae5", "bd_cluster");
if (!$cnx) {
    die("Erreur de connexion : " . mysqli_connect_error());
}

if (isset($_POST['download_json'])) {
    $sql = "SELECT * FROM logs ORDER BY date DESC;";
    $resultat = mysqli_query($cnx, $sql);
    $logs = [];
    while ($row = mysqli_fetch_assoc($resultat)) {
        $logs[] = $row;
    }
    header('Content-Type: application/json');
    header('Content-Disposition: attachment; filename="logs.json"');
    echo json_encode($logs, JSON_PRETTY_PRINT);
    mysqli_close($cnx);
    exit();
}

include_once "../templates/header.html";

echo "
<title>Logs</title>
<style>
   table {
       width: 70%;
       border-collapse: collapse;
       margin: auto;
       margin-bottom: 300px;
       font-size: 18px;
   }

   th, td {
       border: 1px solid #ddd;
       padding: 15px;
       text-align: center;
   }

   th {
       background-color: #1e1e2f;
       color: white;
       font-weight: bold;
   }

   tr:hover {
       background-color: #ddd;
   }

   .button_barnav {
        background-color: #1e1e2f;
        color: #fff;
        border: 2px solid;
        padding: 20px;
        margin-top: 20px;
        margin-bottom: 80px;
        font-size: 18px;
        border-radius: 8px;
        cursor: pointer;
        display: block;
        margin-left: auto;
        margin-right: auto;
        transition: background-color 0.3s;
    }
    
    .button_barnav:hover {
        border: 2px solid #1e1e2f ;
        background-color: lightgrey;
        color: #1e1e2f;
    }
</style>
</head>
<body>
";

include_once "../gestion/fonctions.php";
afficherBarnav();

echo "<h1 style='text-align: center; color: #1c305f; margin-top: 80px; margin-bottom: 80px;'>Base des Logs</h1>";

echo "
<form method='post'>
   <button type='submit' class='button_barnav' name='download_json'>Télécharger les logs en JSON</button>
</form>
";

$sql = "SELECT * FROM logs ORDER BY date DESC;";
$resultat = mysqli_query($cnx, $sql);
$resultat = mysqli_query($cnx, $sql);
if (!$resultat) {
    die("Erreur SQL : " . mysqli_error($cnx));
}

echo "<table>";
$lignes = mysqli_fetch_assoc($resultat);
if ($lignes) {
    echo "<tr>";
    foreach ($lignes as $key => $value) {
        echo "<th>$key</th>";
    }
    echo "</tr>";

    do {
        echo "<tr>";
        foreach ($lignes as $key => $value) {
            if ($key == 'date') {
                $value = date('d/m/Y H:i:s', strtotime($value));
            }
            echo "<td>$value</td>";
        }
        echo "</tr>";
    } while ($lignes = mysqli_fetch_assoc($resultat));
} else {
    echo "<tr><td colspan='100%' style='text-align: center;'>La table des logs est vide.</td></tr>";
}
echo "</table>";

mysqli_close($cnx);

include_once "../templates/footer.html";