<?php
require_once 'cont_client.php';

class ModClient {
    public function __construct() {
        $controleur = new ContClient();
        
if (isset($_GET['action'])) {
    $action = $_GET['action'];
} else {
    $action = 'compte';
}

        switch ($action) {

            case 'ajouterAuPanier':
                $controleur->ajouterAuPanier();
                break;

            case 'retirerDuPanier':
                $controleur->retirerDuPanier();
                break;

            case 'genererCodePanier':
                $controleur->genererCodePanier();
                break;

            case 'compte':
            default:
                $controleur->afficherCompte();
                break;
        }
    }
}
?>
