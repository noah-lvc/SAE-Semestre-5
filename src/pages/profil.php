<?php
include_once "../templates/header.html";

echo "
<title>Profil</title>
</head>
<body>";

include_once "../templates/barnav.html";

echo "
<div class='profil-container'>
    <div class='pfp'>
    <img src='../images/logo.jpg' alt='Logo du site' class='logo'>
</div>

    <form class='profil-form' method='post'>
        <input type='password' name='AncienMDP' placeholder='Ancien mot de passe' minlength='6' required>
        <input type='password' name='NouveauMDP' placeholder='Nouveau mot de passe' minlength='6' required>
        <input type='password' name='ConfirmerMdp' placeholder='Réécrire nouveau mot de passe' minlength='6' required>
        <button type='submit' name='ModifierMDP'>Confirmer</button>
    </form>
    
    <div class='profil-actions'>
        <button class='logout-btn'>Déconnexion</button>
        <button class='delete-btn'>Supprimer compte</button>
    </div>

    
</div>";


include_once "../templates/footer.html";
