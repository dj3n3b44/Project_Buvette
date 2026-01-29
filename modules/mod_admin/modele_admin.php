<?php
require_once 'connexion.php';

class ModeleAdmin extends Connexion {


    public function getLesAssociations() {
        $sql = "SELECT * FROM Association ORDER BY nom ASC";
        $req = self::$bdd->query($sql);
        return $req->fetchAll();
    }

    public function creerAssociation($nom, $code_acces) {
        try {
            $verif = self::$bdd->prepare("SELECT id_association FROM Association WHERE code_acces = ?");
            $verif->execute([$code_acces]);
            if ($verif->fetch()) return false;

            $sql = "INSERT INTO Association (nom, code_acces) VALUES (?, ?)";
            $req = self::$bdd->prepare($sql);
            $req->execute([$nom, $code_acces]);
            return true;
        } catch (Exception $e) { return false; }
    }

    public function supprimerAssociation($id_asso) {
        $sql = "DELETE FROM Association WHERE id_association = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_asso]);
    }

    public function getNbTotalUtilisateurs() {
        $req = self::$bdd->query("SELECT COUNT(*) FROM Utilisateur");
        return $req->fetchColumn();
    }


    public function getNomAsso($id_asso) {
        $req = self::$bdd->prepare("SELECT nom FROM Association WHERE id_association = ?");
        $req->execute([$id_asso]);
        return $req->fetchColumn();
    }

    public function getMembresByAsso($id_asso) {
        $sql = "SELECT U.id_user, U.nom, U.prenom, U.email, M.id_role, R.nom as role_nom
                FROM Utilisateur U
                JOIN Membre M ON U.id_user = M.id_user
                JOIN Role R ON M.id_role = R.id_role
                WHERE M.id_association = ?
                ORDER BY M.id_role DESC, U.nom ASC";

        $req = self::$bdd->prepare($sql);
        $req->execute([$id_asso]);
        return $req->fetchAll();
    }

    public function updateRoleMembre($id_user, $id_asso, $new_role) {
        $sql = "UPDATE Membre SET id_role = ? WHERE id_user = ? AND id_association = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$new_role, $id_user, $id_asso]);
    }
}
?>