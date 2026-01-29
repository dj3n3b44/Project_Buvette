<?php
require_once 'connexion.php';

class ModeleClient extends Connexion {

    public function getInfosClient($id_user) {
        $sql = "SELECT prenom, nom, solde FROM Utilisateur WHERE id_user = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_user]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function getHistoriqueAchats($id_user) {
        $id_asso = $_SESSION['id_asso_active'];
        $sql = "SELECT date_hist as date_transaction,
                       montant,
                       type_operation
                FROM Historique
                WHERE id_client = ? AND id_association = ?
                ORDER BY date_hist DESC";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_user, $id_asso]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduitsDisponibles() {
        $id_asso = $_SESSION['id_asso_active'] ?? 0;
        $sql = "SELECT id_produit, nom, prix_vente AS prix FROM Produit
                WHERE stock_actuel > 0 AND id_association = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_asso]);
        return $req->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProduit($id_produit) {
        $sql = "SELECT nom, prix_vente FROM Produit WHERE id_produit = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$id_produit]);
        return $req->fetch(PDO::FETCH_ASSOC);
    }

    public function calculerTotalPanier($panier) {
        $total = 0;
        foreach ($panier as $id => $qte) {
            $produit = $this->getProduit($id);
            if ($produit) {
                $total += $produit['prix_vente'] * $qte;
            }
        }
        return $total;
    }

    public function creerPanierValidation($idUser, $code, $contenu_array, $total) {
        $id_asso = $_SESSION['id_asso_active'] ?? 0;
        $contenu_json = json_encode($contenu_array);

        $sql = "INSERT INTO Panier_validation (code_validation, id_client, contenu, montant_total, etat, id_association)
                VALUES (?, ?, ?, ?, 'en_attente', ?)";

        $req = self::$bdd->prepare($sql);
        $req->execute([$code, $idUser, $contenu_json, $total, $id_asso]);
    }

    public function getStatutPanier($code) {
        $sql = "SELECT etat FROM Panier_validation WHERE code_validation = ?";
        $req = self::$bdd->prepare($sql);
        $req->execute([$code]);
        return $req->fetchColumn();
    }
}
?>