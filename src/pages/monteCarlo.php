<?php
session_start();
include_once "../templates/header.html";

echo "
<title>Monte Carlo - Calcul de PI</title>
<style>
    .container {
        width: 55%;
        margin: 100px auto;
        padding: 40px;
        background-color: white;
        box-shadow: 0 0 15px rgba(0,0,0,0.2);
        border-radius: 10px;
    }

    h1 {
        text-align: center;
        color: #1e1e2f;
        margin-bottom: 40px;
    }

    form {
        text-align: center;
    }

    input[type='number'] {
        padding: 10px;
        font-size: 18px;
        width: 200px;
        margin-bottom: 20px;
        text-align: center;
    }

    input[type='submit'] {
        padding: 10px 25px;
        font-size: 16px;
        background-color: #1e1e2f;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 5px;
    }

    pre {
        background-color: #eee;
        padding: 20px;
        border-radius: 5px;
        margin-top: 30px;
        white-space: pre-wrap;
    }
</style>
</head>
<body>
";

include_once "../gestion/fonctions.php";
afficherBarnav();

echo "
<div class='container'>
<h1>Calcul de PI – Monte Carlo Distribué</h1>

<form method='post'>
    <label>Nombre de workers (1 à 4)</label><br><br>
    <input type='number' name='nb_workers' min='1' max='4' value='4' required><br><br>
    <input type='submit' name='run' value='Lancer le calcul'>
</form>
";

if (isset($_POST['run'])) {

    $nb_workers = intval($_POST['nb_workers']);
    $all_workers = [
        ["user" => "rpi01", "ip" => "172.19.181.1", "pass" => "rpi01", "port" => 25545],
        ["user" => "rpi02", "ip" => "172.19.181.2", "pass" => "rpi02", "port" => 25546],
        ["user" => "rpi03", "ip" => "172.19.181.3", "pass" => "rpi03", "port" => 25547],
        ["user" => "rpi04", "ip" => "172.19.181.4", "pass" => "rpi04", "port" => 25548],
    ];
    $workers = array_slice($all_workers, 0, $nb_workers);

    // lanncement des workers
    foreach ($workers as $w) {
        shell_exec(sprintf(
            'sshpass -p %s ssh -o StrictHostKeyChecking=no %s@%s "/usr/bin/java WorkerSocket %d > /tmp/worker_%d.log 2>&1 & disown"',
            escapeshellarg($w["pass"]),
            $w["user"],
            $w["ip"],
            $w["port"],
            $w["port"]
        ));
    }

    sleep(2);

    // mastersocket
    $stdin = $nb_workers . "\n";
    foreach ($workers as $w) {
        $stdin .= $w["port"] . "\n";
    }
    $stdin .= "n\n";

    $log = "/tmp/master_output.log";
    shell_exec("cd /opt/master && printf \"$stdin\" | /usr/bin/java MasterSocket > $log 2>&1");

    // filtrage de la sortie
    if (file_exists($log)) {

        $lines = explode("\n", file_get_contents($log));
        $filtered = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (
                str_starts_with($line, "Pi :") ||
                str_starts_with($line, "Error:") ||
                str_starts_with($line, "Ntot:") ||
                str_starts_with($line, "Available processors") ||
                str_starts_with($line, "Time Duration") ||
                preg_match('/^[0-9.E\-]+ [0-9]+ [0-9]+ [0-9]+$/', $line)
            ) {
                $filtered[] = $line;
            }
        }
        echo "<pre>" . htmlspecialchars(implode("\n", $filtered)) . "</pre>";
    } else {
        echo "<pre>Erreur : aucun log généré.</pre>";
    }
}

echo "</div>";
include_once "../templates/footer.html";
?>
