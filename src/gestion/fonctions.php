<?php
function afficherBarnav() {
    if (!isset($_SESSION)) {
        session_start();
    }

    if (isset($_SESSION['profil'])) {
        switch ($_SESSION['profil']) {
            case 'adminweb':
                include '../templates/barnavAdminWeb.html';
                break;
            case 'adminsys':
                include '../templates/barnavAdminSys.html';
                break;
            case 'connected':
                include '../templates/barnavConnected.php';
                break;
            default:
                include '../templates/barnav.php';
                break;
        }
    } else {
        include '../templates/barnav.php';
    }
}