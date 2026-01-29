<?php
require_once 'cont_choix_asso.php';

class ModChoixAsso {
    public function __construct() {
        $controleur = new ContChoixAsso();
        $action = $_GET['action'] ?? 'liste';

        switch ($action) {
            case 'rejoindre':
                $controleur->traiterDemande();
                break;
            case 'entrer':
                $controleur->validerChoix();
                break;
            default:
                $controleur->afficherListe();
                break;
        }
    }
}