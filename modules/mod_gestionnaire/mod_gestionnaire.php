<?php
require_once 'cont_gestionnaire.php';

class ModGestionnaire {
    public function __construct() {
        $controleur = new ContGestionnaire();

        if (!isset($_SESSION['role']) || $_SESSION['role'] != 4) {
            echo "Accès Interdit.";
            return;
        }

        $action = $_GET['action'] ?? 'dashboard';

        switch ($action) {
            case 'membres':
                $controleur->gererMembres();
                break;
            case 'ajouter_membre':
                $controleur->ajouterMembre();
                break;
            case 'modifier_role':
                $controleur->modifierRole();
                break;

            case 'form_barman':
                $controleur->afficherFormBarman();
                break;
            case 'creer_barman':
                $controleur->traiterCreationBarman();
                break;

            case 'form_stock':
                $controleur->afficherFormStock();
                break;
            case 'valider_stock':
                $controleur->traiterAjoutStock();
                break;

             case 'historique_stock':
                $controleur->voirHistoriqueStocks();
                break;

            case 'dashboard':
            default:
                $controleur->accueil();
                break;
        }
    }
}
?>