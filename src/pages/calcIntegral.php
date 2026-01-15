<?php
session_start();
include_once "../templates/header.html";

echo "
<title>Simpson - Calcul d'intégrale</title>
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
<script>
function toggleRemote() {
    const local = parseInt(document.getElementById('local_workers').value);
    const remoteInput = document.getElementById('remote_workers');

    if (local === 4) {
        remoteInput.disabled = false;
    } else {
        remoteInput.value = 0;
        remoteInput.disabled = true;
    }
}

window.onload = toggleRemote;
</script>
</head>
<body>
";

include_once "../gestion/fonctions.php";
afficherBarnav();

echo "
<div class='container'>
<h1>Calcul d'intégrale – Simpson Distribué</h1>

<form method='post'>
    <label>Nombre de workers locaux (1 à 4)</label><br><br>
    <input type='number' id='local_workers' name='local_workers' min='1' max='4' value='4' required onchange='toggleRemote()'><br><br>

    <label>Nombre de workers distants (0 à 4)</label><br><br>
    <input type='number' id='remote_workers' name='remote_workers' min='0' max='4' value='0' disabled required><br><br>

    <label>A (début)</label><br>
    <input type='number' step='any' name='A' value='0' required><br>

    <label>B (fin)</label><br>
    <input type='number' step='any' name='B' value='1' required><br>

    <label>Nombre total de sous-intervalles (pair)</label><br>
    <input type='number' name='totalN' value='1000' required><br><br>

    <input type='submit' name='run' value='Lancer le calcul'>
</form>
";

if (isset($_POST['run'])) {

    $local  = intval($_POST['local_workers']);
    $remote = intval($_POST['remote_workers']);

    if ($local < 1 || $local > 4) {
        die("Nombre de workers locaux invalide");
    }

    if ($local < 4 && $remote > 0) {
        die("Workers distants interdits sans 4 workers locaux");
    }

    if ($remote < 0 || $remote > 4) {
        die("Nombre de workers distants invalide");
    }
    $A = $_POST['A'];
    $B = $_POST['B'];
    $totalN = intval($_POST['totalN']);

    // Lancement workers locaux
    $local_ports = [25550, 25551, 25552, 25553];
    $local_ports = array_slice($local_ports, 0, $local);
    foreach ($local_ports as $p) {
        shell_exec("cd /opt/master && java WorkerSimpsonSocket $p > /tmp/worker_simpson_$p.log 2>&1 &");
    }

    // Workers distants
    $remote_workers = [
        ["user"=>"rpi01","ip"=>"172.19.181.1","pass"=>"rpi01","port"=>25554],
        ["user"=>"rpi02","ip"=>"172.19.181.2","pass"=>"rpi02","port"=>25555],
        ["user"=>"rpi03","ip"=>"172.19.181.3","pass"=>"rpi03","port"=>25556],
        ["user"=>"rpi04","ip"=>"172.19.181.4","pass"=>"rpi04","port"=>25557],
    ];

    for ($i = 0; $i < $remote; $i++) {
        $w = $remote_workers[$i];
        shell_exec(sprintf(
            'sshpass -p %s ssh -o StrictHostKeyChecking=no %s@%s "java WorkerSimpsonSocket %d > /tmp/worker_simpson_%d.log 2>&1 &"',
            escapeshellarg($w["pass"]),
            $w["user"],
            $w["ip"],
            $w["port"],
            $w["port"]
        ));
    }

    sleep(2);

    // Master
    $total_workers = $local + $remote;

    $stdin = ""; // Simpson master ne lit pas depuis stdin, mais on prépare ports si nécessaire
    foreach ($local_ports as $p) $stdin .= "$p\n";
    for ($i = 0; $i < $remote; $i++) $stdin .= $remote_workers[$i]["port"] . "\n";

    $log = "/tmp/master_simpson_output.log";
    shell_exec(sprintf(
        'cd /opt/master && /usr/bin/java MasterSimpsonSocket %s %s %d %d > %s 2>&1',
        escapeshellarg($A),
        escapeshellarg($B),
        $totalN,
        $total_workers,
        $log
    ));

    // Affichage
    if (file_exists($log)) {
        $lines = explode("\n", file_get_contents($log));
        $filtered = [];
        foreach ($lines as $line) {
            $line = trim($line);
            if (
                str_starts_with($line, "Interval:") ||
                str_starts_with($line, "Total sub-intervals") ||
                str_starts_with($line, "Workers:") ||
                str_starts_with($line, "Sub-intervals per worker") ||
                str_starts_with($line, "Integral") ||
                str_starts_with($line, "Execution time")
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
