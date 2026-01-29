<?php
require_once 'cont_barman.php';

class ModBarman {
    public function __construct() {
        $controleur = new ContBarman();

        $action = isset($_GET['action']) ? $_GET['action'] : 'accueil';

        switch ($action) {

            case 'inventaire':
                $controleur->formulaireInventaire();
                break;

            case 'ajouter_produit':
                $controleur->ajouterNouveauProduit();
                break;

            case 'supprimer_produit':
                $controleur->supprimerProduit();
                break;

            case 'valider_inventaire':
                $controleur->validerInventaire();
                break;

            case 'historique':
                $controleur->voirHistorique();
                break;

            case 'valider_code':
                $controleur->validerCodePanier();
                break;

            case 'recharge':
                $controleur->formulaireRecharge();
                break;

            case 'valider_recharge':
                $controleur->validerRecharge();
                break;

            case 'commandes':
                $controleur->voirCommandesEnCours();
                break;

            case 'annuler_commande':
                $controleur->annulerCommandeEnCours();
                break;

            case 'ajouter_manuel':
                $controleur->ajouterPanierManuel();
                break;

            case 'terminer_commande':
                $controleur->terminerCommande();
                break;

            case 'accueil':
            default:
                $controleur->accueil();
                break;
        }
    }
}
?>