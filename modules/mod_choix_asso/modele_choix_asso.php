<?php
require_once 'connexion.php';

class ModeleChoixAsso extends Connexion {

    public function getMesAssociations($id_user) {
        $sql = "SELECT A.*, R.nom as nom_role, M.id_role
                FROM Association A
                JOIN Membre M ON A.id_association = M.id_association
                JOIN Role R ON M.id_role = R.id_role
                WHERE M.id_user = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_user]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>