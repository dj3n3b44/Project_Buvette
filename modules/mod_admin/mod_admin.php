<?php
require_once 'cont_admin.php';

class ModAdmin {
    public function __construct() {

        $role = $_SESSION['id_role'] ?? $_SESSION['role'] ?? 0;

        if ($role != 1) {
            die("<h3>Accès Refusé. Vous n'êtes pas Administrateur.</h3>");
        }

        $controleur = new ContAdmin();
        $action = isset($_GET['action']) ? $_GET['action'] : 'dashboard';

        switch ($action) {
            case 'creer_asso':
                $controleur->traiterCreationAsso();
                break;
            case 'supprimer_asso':
                $controleur->supprimerAsso();
                break;

            case 'voir_membres':
                $controleur->voirMembresAsso();
                break;
            case 'promouvoir_gestionnaire':
                $controleur->definirGestionnaire();
                break;

            case 'dashboard':
            default:
                $controleur->afficherListeAssos();
                break;
        }
    }
}
?>