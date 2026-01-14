<?php
session_start();
include_once "../templates/header.html";

echo "
<title>Module Prime</title>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    .container {
        width: 50%;
        margin: 100px auto;
        padding: 40px;
        background-color: white;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        border-radius: 10px;
    }

    h1 {
        text-align: center;
        color: #1e1e2f;
        margin-bottom: 50px;
    }

    form {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    input[type='number'] {
        padding: 10px;
        font-size: 18px;
        width: 60%;
        margin-bottom: 20px;
        border-radius: 5px;
        border: 1px solid #ccc;
        text-align: center;
    }

    input[type='submit'] {
        padding: 10px 30px;
        font-size: 18px;
        background-color: #1e1e2f;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    input[type='submit']:hover {
        background-color: #3b3b5c;
    }

    pre {
        background-color: #eee;
        padding: 20px;
        border-radius: 5px;
        overflow-x: auto;
        max-height: 400px;
    }
</style>
</head>
<body>
";

include_once "../gestion/fonctions.php";
afficherBarnav();

echo "
<div class='container'>
    <h1>Calcul de nombres premiers</h1>
    <form method='post'>
        <input type='number' name='end_number' placeholder='Entrez un nombre' min='2' required>
        <input type='submit' name='run_prime' value='Lancer le calcul'>
    </form>
";

if (isset($_POST['run_prime'])) {
    $end_number = intval($_POST['end_number']);
    if ($end_number < 2) {
        echo "<p style='color:red; text-align:center;'>Veuillez entrer un nombre supérieur ou égal à 2.</p>";
    } else {
        echo "<h3 style='text-align:center;'>Résultat pour end_number = $end_number</h3>";
        echo "<pre>";
        $command = "sudo -u mpiuser /usr/bin/mpirun --mca plm_rsh_no_tree_spawn 1 -np 5 -hostfile /home/mpiuser/hosts /usr/bin/python3 /home/mpiuser/prime.py $end_number 2>&1";
        $output = shell_exec($command);
        echo htmlspecialchars($output);
        echo "</pre>";
    }
}

echo "</div>";
include_once "../templates/footer.html";
?>
