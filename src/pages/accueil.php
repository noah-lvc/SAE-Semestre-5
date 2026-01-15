<?php
session_start();
include_once "../templates/header.html";

echo"
<title>Accueil</title>
</head>
<body>";

include_once "../gestion/fonctions.php";
afficherBarnav();

echo "
<div class='video-carousel'>
    <button class='carousel-btn left'>&lt;</button>

    <div class='carousel-track'>
        <video class='carousel-item left' autoplay loop muted playsinline>
            <source src='../videos/video2.mp4' type='video/mp4'>
            Votre navigateur ne prend pas en charge cette vidéo.
        </video>
        <video class='carousel-item center' autoplay loop muted playsinline>
            <source src='../videos/video1.mov' type='video/mp4'>
            Votre navigateur ne prend pas en charge cette vidéo.
        </video>
        <video class='carousel-item right' autoplay loop muted playsinline>
            <source src='../videos/video3.mp4' type='video/mp4'>
            Votre navigateur ne prend pas en charge cette vidéo.
        </video>
    </div>

    <button class='carousel-btn right'>&gt;</button>
</div>

<div class='TexteExplicatif'>
    <h1>Bienvenue sur ClusterLab, la plateforme de calcul distribué</h1>
    <p>C'est plus précisement une application web conçue pour exploiter un cluster Raspberry et réaliser des calculs distribués ou parallèles de manière simple et efficace. Elle permet aux utilisateurs inscrits de lancer des programmes de calcul et d’obtenir des résultats directement dans leur interface web.</p>
    <p>Grâce à ClusterLab, vous pouvez :</p>
    <ul>
       <li><strong>Lancer des programmes de calcul</strong> : Utilisez nos modules pour exécuter des calculs sur le cluster Raspberry.</li>
       <li><strong>Gérer votre profil</strong> : Créez un compte, modifiez vos informations et accédez à votre tableau de bord personnalisé.</li>
       <li><strong>Suivre vos résultats</strong> : Visualisez les résultats de vos calculs directement sur la plateforme.</li>
    </ul>
    <p>La plateforme est conçue pour être accessible tant aux étudiants qu’aux professeurs souhaitant expérimenter le calcul distribué ou parallèle. Vous trouverez différents modules adaptés à vos besoins et pourrez facilement explorer les possibilités offertes par le cluster Raspberry.</p>
    <p>Nous vous invitons à vous inscrire pour profiter pleinement de toutes les fonctionnalités et commencer à lancer vos programmes de calcul sur le cluster.</p>
    <p>Explorez, testez et optimisez vos calculs !<br>(L'équipe de développement)</p>
</div>
";
include_once "../templates/footer.html";
