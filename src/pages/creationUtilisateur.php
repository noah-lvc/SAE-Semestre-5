<?php
include_once "../templates/header.html";

echo"
<title>Création Utilisateur</title>
</head>
<body>";
include_once "../templates/barnav.html";
echo "
<main class='creation-container'>
    <section class='formulaire-inscription'>
        <h2>Inscription</h2>
        <form>
            <label for='login'>Login</label><br>
            <input type='text' id='login' name='login' placeholder='Entrez un login' required><br>

            <label for='mdp'>Mot de passe</label><br>
            <input type='password' id='mdp' name='mdp' placeholder='Mot de passe' required><br>

            <label for='mdp-confirm'>Confirmer le mot de passe</label><br>
            <input type='password' id='mdp-confirm' name='mdp-confirm' placeholder='Confirmez le mot de passe' required><br>

            <button type='submit'>Confirmer</button>
        </form>
    </section>

    <section class='import-csv'>
        <h2>Ajouter via CSV</h2>
        <form enctype='multipart/form-data'>
            <label for='fichier-csv'>Importer un fichier CSV :</label><br>
            <input type='file' id='fichier-csv' name='fichier-csv' accept='.csv' required><br>
            <button type='submit'>Importer</button>
        </form>
    </section>
</main>

";
include_once "../templates/footer.html";