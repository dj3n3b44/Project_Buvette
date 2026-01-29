<?php
session_start();
require_once "connexion.php";
Connexion::initConnexion();

$module = $_GET['module'] ?? 'accueil';

if (!isset($_SESSION['id_user']) && $module !== 'connexion') {
    header("Location: index.php?module=connexion");
    exit();
}

if (isset($_SESSION['id_user']) && !isset($_SESSION['id_asso_active'])) {
    if ($module !== 'choix_asso' && $module !== 'connexion') {
        header("Location: index.php?module=choix_asso");
        exit();
    }
}

ob_start();

switch ($module) {
    case 'connexion':
        require_once "modules/mod_connexion/mod_connexion.php";
        new ModConnexion();
        break;

    case 'choix_asso':
        require_once "modules/mod_choix_asso/mod_choix_asso.php";
        new ModChoixAsso();
        break;

    case 'barman':
        if ($_SESSION['role'] != 2) { header("Location: index.php"); exit(); }
        require_once "modules/mod_barman/mod_barman.php";
        new ModBarman();
        break;

    case 'client':
        if ($_SESSION['role'] != 3) { header("Location: index.php"); exit(); }
        require_once "modules/mod_client/mod_client.php";
        new ModClient();
        break;

    case 'gestionnaire':
        if ($_SESSION['role'] != 4) { header("Location: index.php"); exit(); }
        require_once "modules/mod_gestionnaire/mod_gestionnaire.php";
        new ModGestionnaire();
        break;

    case 'admin':
        if ($_SESSION['role'] != 1) { header("Location: index.php"); exit(); }
        require_once "modules/mod_admin/mod_admin.php";
        new ModAdmin();
        break;

    case 'accueil':
    default:
        $roles = [1 => 'admin', 2 => 'barman', 3 => 'client', 4 => 'gestionnaire'];
        $cible = $roles[$_SESSION['role']] ?? 'connexion';
        header("Location: index.php?module=$cible");
        exit();
}

$affichageModule = ob_get_clean();
require_once "template.php";
?>