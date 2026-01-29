<?php
require_once 'modele_admin.php';
require_once 'vue_admin.php';

class ContAdmin {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleAdmin();
        $this->vue = new VueAdmin();
    }

    // --- DASHBOARD PRINCIPAL ---
    public function afficherListeAssos() {
        $lesAssos = $this->modele->getLesAssociations();
        $nbUsers = $this->modele->getNbTotalUtilisateurs();
        $this->vue->afficherDashboard($lesAssos, $nbUsers);
    }

    public function traiterCreationAsso() {
        if (!empty($_POST['nom']) && !empty($_POST['code'])) {
            $succes = $this->modele->creerAssociation($_POST['nom'], $_POST['code']);
            if ($succes) echo "<div class='alert alert-success container mt-3'>Association créée !</div>";
            else echo "<div class='alert alert-danger container mt-3'>Code déjà pris.</div>";
        }
        $this->afficherListeAssos();
    }

    public function supprimerAsso() {
        if (isset($_GET['id'])) {
            $this->modele->supprimerAssociation($_GET['id']);
        }
        $this->afficherListeAssos();
    }


    public function voirMembresAsso() {
        if (isset($_GET['id'])) {
            $id_asso = $_GET['id'];
            $nom_asso = $this->modele->getNomAsso($id_asso);
            $membres = $this->modele->getMembresByAsso($id_asso);

            $this->vue->afficherListeMembres($id_asso, $nom_asso, $membres);
        } else {
            $this->afficherListeAssos();
        }
    }

    public function definirGestionnaire() {
        if (isset($_POST['id_user']) && isset($_POST['id_asso'])) {
            $id_user = $_POST['id_user'];
            $id_asso = $_POST['id_asso'];

            $this->modele->updateRoleMembre($id_user, $id_asso, 4);

            echo "<div class='alert alert-success container mt-3'>Le membre a été nommé Gestionnaire !</div>";

            header("Location: index.php?module=admin&action=voir_membres&id=$id_asso");
            exit();
        }
        $this->afficherListeAssos();
    }
}
?>