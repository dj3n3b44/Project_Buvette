<?php
require_once 'modules/mod_connexion/modele_connexion.php';
require_once 'vue_choix_asso.php';

class ContChoixAsso {
    private $modele;
    private $vue;

    public function __construct() {
        $this->modele = new ModeleConnexion();
        $this->vue = new VueChoixAsso();
    }

    public function afficherListe($message = null) {
        $actives = $this->modele->getAssociationsUtilisateur($_SESSION['id_user']);
        $this->vue->afficherListe($actives, $message);
    }

    public function traiterDemande() {
        if (isset($_POST['code_asso'])) {
            $res = $this->modele->rejoindreAssoDirectement($_SESSION['id_user'], $_POST['code_asso']);

            $message = null;
            if ($res === true) {
                $message = "<div class='alert alert-success'>Association rejointe avec succès !</div>";
            } elseif ($res === "deja_membre") {
                $message = "<div class='alert alert-info'>Vous êtes déjà membre de cette association.</div>";
            } else {
                $message = "<div class='alert alert-danger'>Code secret invalide.</div>";
            }
            $this->afficherListe($message);
        }
    }

    public function validerChoix() {
        if (isset($_GET['id_asso']) && isset($_GET['id_role'])) {
            $id_asso = $_GET['id_asso'];
            $id_role = $_GET['id_role'];

            $actives = $this->modele->getAssociationsUtilisateur($_SESSION['id_user']);
            foreach ($actives as $a) {
                if ($a['id_association'] == $id_asso && $a['id_role'] == $id_role) {
                    $_SESSION['id_asso_active'] = $a['id_association'];
                    $_SESSION['role'] = $a['id_role'];
                    $_SESSION['statut'] = $a['nom_role'];

                    header("Location: index.php?module=accueil");
                    exit();
                }
            }
        }
        header("Location: index.php?module=choix_asso");
    }
}
?>