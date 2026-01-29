<?php
require_once 'modele_accueil.php';
require_once 'vue_accueil.php';

class ContAccueil {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleAccueil();
        $this->vue = new VueAccueil();
    }

    public function afficher() {
        if (isset($_GET['code'])) {
            $this->traiterCode($_GET['code']);
        } else {
            $this->vue->afficherFormulaireCode();
        }
    }

    public function chercherAsso() {
        if (isset($_POST['code_asso'])) {
            $this->traiterCode($_POST['code_asso']);
        } else {
            $this->afficher();
        }
    }

    private function traiterCode($code) {

        $asso = $this->modele->trouverAssoParCode($code);

        if ($asso) {
            $id = $asso['id_association'];
            header("Location: index.php?module=connexion&id_asso=$id");
            exit();
        } else {
            $this->vue->afficherFormulaireCode("Code association introuvable.");
        }
    }
}
?>