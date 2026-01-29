<?php
require_once 'connexion.php';

class ModeleConnexion extends Connexion {

    public function verifierUtilisateurGlobal($email, $mdp_saisi) {
        $sql = "SELECT * FROM Utilisateur WHERE email = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$email]);
        $user = $req->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mdp_saisi, $user['mdp'])) {
            return $user;
        }
        return false;
    }

    public function inscrireUtilisateur($nom, $prenom, $email, $mdp) {
        $check = self::$bdd->prepare("SELECT id_user FROM Utilisateur WHERE email = ?");
        $check->execute([$email]);
        if ($check->rowCount() > 0) {
            return "email_existant";
        }

        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        $sql = "INSERT INTO Utilisateur (nom, prenom, email, mdp, solde) VALUES (?, ?, ?, ?, 0)";
        $req = self::$bdd->prepare($sql);
        return $req->execute([$nom, $prenom, $email, $hash]);
    }

    public function getAssociationsUtilisateur($id_user) {
        $sql = "SELECT a.id_association, a.nom, m.id_role, r.nom as nom_role
                FROM Association a
                JOIN Membre m ON a.id_association = m.id_association
                JOIN Role r ON m.id_role = r.id_role
                WHERE m.id_user = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_user]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function rejoindreAssoDirectement($id_user, $code_asso, $id_role = 3) {
        $sqlAsso = "SELECT id_association FROM Association WHERE code_acces = ?";
        $reqAsso = self::$bdd->prepare($sqlAsso);
        $reqAsso->execute([$code_asso]);
        $asso = $reqAsso->fetch();

        if ($asso) {
            $id_asso = $asso['id_association'];

            $sqlCheck = "SELECT * FROM Membre WHERE id_user = ? AND id_association = ? AND id_role = ?";
            $reqCheck = self::$bdd->prepare($sqlCheck);
            $reqCheck->execute([$id_user, $id_asso, $id_role]);

            if ($reqCheck->rowCount() == 0) {
                $sqlInsert = "INSERT INTO Membre (id_user, id_association, id_role) VALUES (?, ?, ?)";
                $reqInsert = self::$bdd->prepare($sqlInsert);
                return $reqInsert->execute([$id_user, $id_asso, $id_role]);
            }
            return "deja_membre";
        }
        return false;
    }
}
?>