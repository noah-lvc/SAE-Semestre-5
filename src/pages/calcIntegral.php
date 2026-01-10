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
        width: 220px;
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
<h1>Calcul d'intégrale – Simpson Distribué</h1>

<form method='post'>
    <label>A (début)</label><br>
    <input type='number' step='any' name='A' value='0' required><br>

    <label>B (fin)</label><br>
    <input type='number' step='any' name='B' value='1' required><br>

    <label>Nombre total de sous-intervalles (pair)</label><br>
    <input type='number' name='totalN' value='1000' required><br>

    <input type='submit' name='run' value='Lancer le calcul'>
</form>
";

if (isset($_POST['run'])) {

    $A = $_POST['A'];
    $B = $_POST['B'];
    $totalN = intval($_POST['totalN']);

    $workers = [
        ["user" => "rpi01", "ip" => "172.19.181.1", "pass" => "rpi01", "port" => 25550],
        ["user" => "rpi02", "ip" => "172.19.181.2", "pass" => "rpi02", "port" => 25551],
        ["user" => "rpi03", "ip" => "172.19.181.3", "pass" => "rpi03", "port" => 25552],
        ["user" => "rpi04", "ip" => "172.19.181.4", "pass" => "rpi04", "port" => 25553],
    ];

    foreach ($workers as $w) {
        shell_exec(sprintf(
            'sshpass -p %s ssh -o StrictHostKeyChecking=no %s@%s "/usr/bin/java WorkerSimpsonSocket %d > /tmp/worker_simpson_%d.log 2>&1 & disown"',
            escapeshellarg($w["pass"]),
            $w["user"],
            $w["ip"],
            $w["port"],
            $w["port"]
        ));
    }

    sleep(2);

    $log = "/tmp/master_simpson_output.log";
    shell_exec(sprintf(
        'cd /opt/master && /usr/bin/java MasterSimpsonSocket %s %s %d > %s 2>&1',
        escapeshellarg($A),
        escapeshellarg($B),
        $totalN,
        $log
    ));

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
