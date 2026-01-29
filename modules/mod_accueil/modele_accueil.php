<?php
require_once 'connexion.php';

class ModeleAccueil extends Connexion {

    public function trouverAssoParCode($code) {
        $sql = "SELECT * FROM Association WHERE code_acces = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$code]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }
}
?>