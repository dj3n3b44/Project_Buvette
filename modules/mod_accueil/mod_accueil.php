<?php
require_once 'cont_accueil.php';

class ModAccueil {
    public function __construct() {
        $controleur = new ContAccueil();

        if (isset($_GET['action']) && $_GET['action'] == 'chercher') {
            $controleur->chercherAsso();
        } else {
            $controleur->afficher();
        }
    }
}
?>