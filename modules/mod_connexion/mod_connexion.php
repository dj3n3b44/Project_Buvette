<?php
require_once 'cont_connexion.php';

class ModConnexion {
    public function __construct() {
        $controleur = new ContConnexion();

        $action = isset($_GET['action']) ? $_GET['action'] : 'formulaire';

        switch ($action) {
            case 'inscription':
                $controleur->formulaireInscription();
                break;
            case 'traiter_inscription':
                $controleur->traiterInscription();
                break;
            case 'connecter':
                $controleur->connecter();
                break;
            case 'deconnecter':
                $controleur->deconnecter();
                break;
            case 'formulaire':
            default:
                $controleur->afficher();
                break;
        }
    }
}
?>