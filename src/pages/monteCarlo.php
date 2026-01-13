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
h1 { text-align: center; color: #1e1e2f; margin-bottom: 40px; }
form { text-align: center; }
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
    <label>Nombre de workers distants (0 à 4)</label><br><br>
    <input type='number' name='remote_workers' min='0' max='4' value='0' required><br><br>

    <label>Nombre total de tirages par worker (Ntot)</label><br><br>
    <input type='number' name='Ntot' min='1' value='400000' required><br><br>

    <input type='submit' name='run' value='Lancer le calcul'>
</form>
";

if (isset($_POST['run'])) {

    $remote = intval($_POST['remote_workers']);
    $Ntot   = intval($_POST['Ntot']);

    $local_ports = [25545, 25546, 25547, 25548];
    foreach ($local_ports as $p) {
        shell_exec("cd /opt/master && java WorkerSocket $p > /tmp/worker_$p.log 2>&1 &");
    }

    $remote_workers = [
        ["user"=>"rpi01","ip"=>"172.19.181.1","pass"=>"rpi01","port"=>25549],
        ["user"=>"rpi02","ip"=>"172.19.181.2","pass"=>"rpi02","port"=>25550],
        ["user"=>"rpi03","ip"=>"172.19.181.3","pass"=>"rpi03","port"=>25551],
        ["user"=>"rpi04","ip"=>"172.19.181.4","pass"=>"rpi04","port"=>25552],
    ];

    for ($i = 0; $i < $remote; $i++) {
        $w = $remote_workers[$i];
        shell_exec(sprintf(
            'sshpass -p %s ssh -o StrictHostKeyChecking=no %s@%s "java WorkerSocket %d > /tmp/worker_%d.log 2>&1 &"',
            escapeshellarg($w["pass"]),
            $w["user"],
            $w["ip"],
            $w["port"],
            $w["port"]
        ));
    }

    sleep(2);

    $total_workers = 4 + $remote;

    $stdin = $total_workers . "\n";
    foreach ($local_ports as $p) $stdin .= "$p\n";
    for ($i = 0; $i < $remote; $i++) $stdin .= $remote_workers[$i]["port"] . "\n";
    $stdin .= "n\n";

    $log = "/tmp/master_output.log";
    shell_exec("cd /opt/master && printf \"$stdin\" | java MasterSocket $Ntot > $log 2>&1");

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
