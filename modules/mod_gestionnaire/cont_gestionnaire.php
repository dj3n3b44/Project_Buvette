<?php
require_once 'modele_gestionnaire.php';
require_once 'vue_gestionnaire.php';

class ContGestionnaire {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleGestionnaire();
        $this->vue = new VueGestionnaire();
    }

    public function accueil() {
        $id_asso = $_SESSION['id_asso_active'];
        $stats = $this->modele->getStatistiques($id_asso);
        $this->vue->afficherDashboard($stats);
    }

    public function afficherFormBarman() {
        $this->vue->formulaireNouveauBarman();
    }

    public function traiterCreationBarman() {
        if (!empty($_POST['email']) && !empty($_POST['mdp'])) {
            $this->modele->creerBarman(
                $_POST['nom'],
                $_POST['prenom'],
                $_POST['email'],
                $_POST['mdp'],
                $_SESSION['id_asso_active']
            );
            echo "<div class='container mt-3 alert alert-success'>Barman ajouté avec succès !</div>";
        }
        $this->accueil();
    }

    public function afficherFormStock() {
        $produits = $this->modele->getProduits($_SESSION['id_asso_active']);
        $this->vue->formulaireAchatStock($produits);
    }

public function traiterAjoutStock() {
    if (isset($_POST['id_produit']) && isset($_POST['quantite'])) {
        $date = !empty($_POST['date_commande']) ? $_POST['date_commande'] : null;
        $this->modele->enregistrerStock(
            $_POST['id_produit'],
            $_POST['quantite'],
            $_POST['cout'],
            $_SESSION['id_asso_active'],
            $date
        );
        echo "<div class='container mt-3 alert alert-success'>Stock mis à jour avec succès !</div>";
    }
    $this->accueil();
}

public function voirHistoriqueStocks() {
    $id_asso = $_SESSION['id_asso_active'];
    $historique = $this->modele->getHistoriqueStocks($id_asso);

    // Debug : décommentez la ligne suivante pour voir si des données arrivent
    // var_dump($historique); die();

    $this->vue->afficherHistoriqueStocks($historique);
}

    public function gererMembres() {
        $membres = $this->modele->getMembresAsso();
        $this->vue->afficherGestionMembres($membres);
    }

    public function ajouterMembre() {
        if (!empty($_POST['email'])) {
            $this->modele->creerMembre($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['mdp'], $_POST['role']);
        }
        $this->gererMembres();
    }

    public function modifierRole() {
        if (isset($_POST['id_user'])) {
            $this->modele->updateRole($_POST['id_user'], $_POST['nouveau_role']);
        }
        $this->gererMembres();
    }
}
?>