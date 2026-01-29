<?php
require_once 'modele_connexion.php';
require_once 'vue_connexion.php';

class ContConnexion {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleConnexion();
        $this->vue = new VueConnexion();
    }

    public function afficher() {
        $this->vue->afficherFormulaire();
    }

    public function connecter() {
        if (!empty($_POST['email']) && !empty($_POST['mdp'])) {
            $user = $this->modele->verifierUtilisateurGlobal($_POST['email'], $_POST['mdp']);

            if ($user) {
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['nom']     = $user['nom'];
                $_SESSION['prenom']  = $user['prenom'];

                header("Location: index.php?module=choix_asso");
                exit();
            } else {
                $this->vue->afficherFormulaire("Identifiants incorrects.");
            }
        } else {
            $this->vue->afficherFormulaire("Veuillez remplir tous les champs.");
        }
    }

    public function formulaireInscription() {
        $this->vue->afficherFormulaireInscription();
    }

    public function traiterInscription() {
        if (!empty($_POST['email']) && !empty($_POST['mdp'])) {
            $res = $this->modele->inscrireUtilisateur($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['mdp']);
            if ($res === true) {
                $this->vue->afficherFormulaire("Compte créé avec succès ! Vous pouvez vous connecter.");
            } else if ($res === "email_existant") {
                $this->vue->afficherFormulaireInscription("Cette adresse email est déjà utilisée.");
            } else {
                $this->vue->afficherFormulaireInscription("Une erreur est survenue.");
            }
        }
    }

    public function deconnecter() {
        session_unset();
        session_destroy();
        header("Location: index.php?module=connexion");
        exit();
    }
}